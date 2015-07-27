import time
import unittest

from selenium import webdriver
from selenium.common.exceptions import WebDriverException
from selenium.webdriver.support.wait import WebDriverWait


class LarePerformanceTestCase(unittest.TestCase):
    request_iterations = 10
    request_timeout = 3
    lare_domain = "https://lare.io"
    angular_domain = "https://angular.io"

    def setUp(self):
        self.browser = webdriver.Firefox()
        self.wait = WebDriverWait(self.browser, 5, poll_frequency=0.1)

    def tearDown(self):
        self.browser.close()
        self.browser.quit()

    def _lare_click(self, site_url_1, site_url_2):
        print "\hline"
        for caching in [{'DB': False, 'TPL': False}, {'DB': True, 'TPL': False}, {'DB': False, 'TPL': True}, {'DB': True, 'TPL': True}]:
            i = 0
            avg_response_time_normal_lare = avg_response_time_normal_angular = avg_response_time_lare = avg_response_time_angular = avg_request_count_normal_lare = avg_request_count_normal_angular = avg_request_count_lare = avg_request_count_angular = avg_waiting_time_normal_lare = avg_waiting_time_normal_angular = avg_waiting_time_lare = avg_waiting_time_angular = 0
            site1 = site_url_1
            site2 = site_url_2
            site1_w_caching = site1+'?db_caching='+str(caching['DB']).lower()+'&tpl_caching='+str(caching['TPL']).lower()
            site2_w_caching = site2+'?db_caching='+str(caching['DB']).lower()+'&tpl_caching='+str(caching['TPL']).lower()
            while i < self.request_iterations:


                # testing angular request
                self.browser.get("{0}{1}".format(self.lare_domain, site2_w_caching))
                time.sleep(self.request_timeout)
                response_time_normal_angular = self.browser.execute_script('return window.performance.getEntries()[window.performance.getEntries().length-1].responseEnd')
                request_count_normal_angular = self.browser.execute_script('return window.performance.getEntries().length')
                waiting_time_normal_angular = self.browser.execute_script('return window.performance.timing.responseStart - window.performance.timing.connectEnd')
                self.browser.get("{0}{1}".format(self.angular_domain, '#'+site1_w_caching))
                time.sleep(self.request_timeout)
                request_count_pre_angular = self.browser.execute_script('return window.performance.getEntries().length')
                link = self.browser.find_element_by_css_selector('a[data-tags-page="{0}"]'.format('#'+site2))
                link.click()
                time.sleep(self.request_timeout)
                request_count_angular = self.browser.execute_script('return window.performance.getEntries().length') - request_count_pre_angular
                try:
                    response_time_angular = self.browser.execute_script('return window.performance.getEntries()[window.performance.getEntries().length-1].responseEnd - window.performance.getEntries()[{0}].startTime'.format(request_count_pre_angular))
                    waiting_time_angular = self.browser.execute_script('return window.performance.getEntries()[{0}].responseStart - window.performance.getEntries()[{0}].connectEnd'.format(request_count_pre_angular))
                except WebDriverException:
                    waiting_time_angular = 0
                    response_time_angular = 0


                # testing normal request
                self.browser.get("{0}{1}".format(self.lare_domain, site2_w_caching))
                time.sleep(self.request_timeout)
                response_time_normal_lare = self.browser.execute_script('return window.performance.getEntries()[window.performance.getEntries().length-1].responseEnd')
                request_count_normal_lare = self.browser.execute_script('return window.performance.getEntries().length')
                waiting_time_normal_lare = self.browser.execute_script('return window.performance.timing.responseStart - window.performance.timing.connectEnd')

                # testing lare request
                self.browser.get("{0}{1}".format(self.lare_domain, site1_w_caching))
                time.sleep(self.request_timeout)
                request_count_pre_lare = self.browser.execute_script('return window.performance.getEntries().length')
                link = self.browser.find_element_by_css_selector('a[href="{0}"]'.format(site2))
                link.click()
                time.sleep(self.request_timeout)
                request_count_lare = self.browser.execute_script('return window.performance.getEntries().length') - request_count_pre_lare
                try:
                    response_time_lare = self.browser.execute_script('return window.performance.getEntries()[window.performance.getEntries().length-1].responseEnd - window.performance.getEntries()[{0}].startTime'.format(request_count_pre_lare))
                    waiting_time_lare = self.browser.execute_script('return window.performance.getEntries()[{0}].responseStart - window.performance.getEntries()[{0}].connectEnd'.format(request_count_pre_lare))
                except WebDriverException:
                    response_time_lare = 0
                    waiting_time_lare = 0
                # adding times and request count to avg value to later divide it through runs.
                avg_response_time_normal_lare += response_time_normal_lare
                avg_response_time_normal_angular += response_time_normal_angular
                avg_response_time_lare += response_time_lare
                avg_response_time_angular += response_time_angular
                avg_request_count_normal_lare += request_count_normal_lare
                avg_request_count_normal_angular += request_count_normal_angular
                avg_request_count_lare += request_count_lare
                avg_request_count_angular += request_count_angular
                avg_waiting_time_normal_lare += waiting_time_normal_lare
                avg_waiting_time_normal_angular += waiting_time_normal_angular
                avg_waiting_time_lare += waiting_time_lare
                avg_waiting_time_angular += waiting_time_angular
                i += 1
            avg_response_time_normal_lare = avg_response_time_normal_lare / self.request_iterations
            avg_response_time_normal_angular = avg_response_time_normal_angular / self.request_iterations
            avg_response_time_lare = avg_response_time_lare / self.request_iterations
            avg_response_time_angular = avg_response_time_angular / self.request_iterations
            avg_request_count_normal_lare = avg_request_count_normal_lare / self.request_iterations
            avg_request_count_normal_angular = avg_request_count_normal_angular / self.request_iterations
            avg_request_count_lare = avg_request_count_lare / self.request_iterations
            avg_request_count_angular = avg_request_count_angular / self.request_iterations
            avg_waiting_time_normal_lare = avg_waiting_time_normal_lare / self.request_iterations
            avg_waiting_time_normal_angular = avg_waiting_time_normal_angular / self.request_iterations
            avg_waiting_time_lare = avg_waiting_time_lare / self.request_iterations
            avg_waiting_time_angular = avg_waiting_time_angular / self.request_iterations
            print "{} & {} & {} & {} & {} & {} & {} & {} \\\\".format(site1, site2, "%.2f" % avg_response_time_normal_lare, "%.2f" % avg_response_time_normal_angular, "%.2f" % avg_response_time_lare, "%.2f" % avg_response_time_angular, '+' if caching['DB'] else '-',  '+' if caching['TPL'] else '-')

            # print "------------------------------------------------------------------------------------------------"
            # print "{0} to {1}".format(site1, site2)
            # print "DB-Caching: {0} TPL-Caching: {1}".format(caching['DB'], caching['TPL'])
            # print "Average initial request lare: {0}ms with {1}ms TTFB (amonut of requests: {2})".format(avg_response_time_normal_lare, avg_waiting_time_normal_lare, avg_request_count_normal_lare)
            # print "Average Lare request: {0}ms with {1}ms TTFB (amonut of requests: {2})".format(avg_response_time_lare, avg_waiting_time_lare, avg_request_count_lare)
            # print "Lare needs {0} % of time".format(avg_response_time_lare/avg_response_time_normal_lare*100)
            # print "-"
            # print "Average initial request angular: {0}ms with {1}ms TTFB (amonut of requests: {2})".format(avg_response_time_normal_angular, avg_waiting_time_normal_angular, avg_request_count_normal_angular)
            # print "Average AngularJS request: {0}ms with {1}ms TTFB (amonut of requests: {2})".format(avg_response_time_angular, avg_waiting_time_angular, avg_request_count_angular)
            # print "AngularJS needs {0} % of time".format(avg_response_time_angular/avg_response_time_normal_lare*100)
        print "\hline"

    def test_a_self(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing self"
        self._lare_click('/', '/')
        self._lare_click('/imprint/', '/imprint/')
        self._lare_click('/tags/p/1/', '/tags/p/1/')
        self._lare_click('/tags/p/2/', '/tags/p/2/')

    def test_b_static_to_static(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing static to static"
        self._lare_click('/', '/imprint/')
        self._lare_click('/imprint/', '/')

    def test_c_dynamic_to_static(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing dynamic to static"
        self._lare_click('/tags/p/1/', '/')
        self._lare_click('/tags/p/2/', '/')
        self._lare_click('/tags/p/1/', '/imprint/')
        self._lare_click('/tags/p/2/', '/imprint/')

    def test_d_dynamic_to_dynamic(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing dynamic to dynamic"
        self._lare_click('/tags/p/1/', '/tags/p/2/')
        self._lare_click('/tags/p/2/', '/tags/p/1/')

    def test_e_static_to_dynamic(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing static to dynamic"
        self._lare_click('/', '/tags/p/1/')
        self._lare_click('/imprint/', '/tags/p/1/')

if __name__ == '__main__':
    unittest.main()