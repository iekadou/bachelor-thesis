import unittest

from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.wait import WebDriverWait
import time


class LarePerformanceTestCase(unittest.TestCase):
    request_iterations = 10
    request_timeout = 3
    domain = "https://lare.io"

    def setUp(self):
        self.browser = webdriver.Firefox()
        self.wait = WebDriverWait(self.browser, 5, poll_frequency=0.1)

    def tearDown(self):
        self.browser.close()
        self.browser.quit()

    def _lare_click(self, site1, site2):
        i = 0
        avg_response_time_normal = avg_response_time_lare = avg_request_count_normal = avg_request_count_lare = avg_waiting_time_normal = avg_waiting_time_lare = 0
        while i < self.request_iterations:

            # testing normal request
            self.browser.get("{0}{1}".format(self.domain, site2))
            time.sleep(self.request_timeout)
            response_time_normal = self.browser.execute_script('return window.performance.getEntries()[window.performance.getEntries().length-1].responseEnd')
            request_count_normal = self.browser.execute_script('return window.performance.getEntries().length')
            waiting_time_normal = self.browser.execute_script('return window.performance.timing.responseStart - window.performance.timing.connectEnd')

            # testing lare request
            self.browser.get("{0}{1}".format(self.domain, site1))
            request_count_pre_lare = self.browser.execute_script('return window.performance.getEntries().length')
            time.sleep(self.request_timeout)
            link = self.browser.find_element_by_css_selector('a[href="{0}"]'.format(site2))
            link.click()
            time.sleep(self.request_timeout)
            response_time_lare = self.browser.execute_script('return window.performance.getEntries()[window.performance.getEntries().length-1].responseEnd - window.performance.getEntries()[{0}].startTime'.format(request_count_pre_lare))
            request_count_lare = self.browser.execute_script('return window.performance.getEntries().length') - request_count_pre_lare
            waiting_time_lare = self.browser.execute_script('return window.performance.getEntries()[{0}].responseStart - window.performance.getEntries()[{0}].connectEnd'.format(request_count_pre_lare))

            # adding times and request count to avg value to later divide it through runs.
            avg_response_time_normal += response_time_normal
            avg_response_time_lare += response_time_lare
            avg_request_count_normal += request_count_normal
            avg_request_count_lare += request_count_lare
            avg_waiting_time_normal += waiting_time_normal
            avg_waiting_time_lare += waiting_time_lare
            i += 1
        print "------------------------------------------------------------------------------------------------"
        avg_response_time_normal = avg_response_time_normal / self.request_iterations
        avg_response_time_lare = avg_response_time_lare / self.request_iterations
        avg_request_count_normal = avg_request_count_normal / self.request_iterations
        avg_request_count_lare = avg_request_count_lare / self.request_iterations
        avg_waiting_time_normal = avg_waiting_time_normal / self.request_iterations
        avg_waiting_time_lare = avg_waiting_time_lare / self.request_iterations
        print "{0} to {1}".format(site1, site2)
        print "Average initial request: {0}ms with {1}ms TTFB (amonut of requests: {2})".format(avg_response_time_normal, avg_waiting_time_normal, avg_request_count_normal)
        print "Average Lare request: {0}ms with {1}ms TTFB (amonut of requests: {2})".format(avg_response_time_lare, avg_waiting_time_lare, avg_request_count_lare)
        print "Lare needs {0} % of time".format(avg_response_time_lare/avg_response_time_normal*100)

    def test_self(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing self"
        self._lare_click('/', '/')
        self._lare_click('/imprint/', '/imprint/')
        self._lare_click('/tags/p/', '/tags/p/')
        self._lare_click('/tags/p/2/', '/tags/p/2/')

    def test_static_to_static(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing static to static"
        self._lare_click('/', '/imprint/')
        self._lare_click('/imprint/', '/')

    def test_dynamic_to_static(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing dynamic to static"
        self._lare_click('/tags/p/', '/')
        self._lare_click('/tags/p/2/', '/')
        self._lare_click('/tags/p/', '/imprint/')
        self._lare_click('/tags/p/2/', '/imprint/')

    def test_dynamic_to_dynamic(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing dynamic to dynamic"
        self._lare_click('/tags/p/', '/tags/p/2/')
        self._lare_click('/tags/p/2/', '/tags/p/')

    def test_static_to_dynamic(self):
        print ""
        print "------------------------------------------------------------------------------------------------"
        print "Testing static to dynamic"
        self._lare_click('/', '/tags/p/')
        self._lare_click('/imprint/', '/tags/p/')


class LareFunctionalityTestCase(unittest.TestCase):
    def setUp(self):
        self.browser = webdriver.Firefox()
        self.wait = WebDriverWait(self.browser, 5, poll_frequency=0.1)

    def test_initial_lare_functionality_additional_ids(self):
        self.browser.get("https://lare.io/")
        self.assertEqual("Lare.io - Home", self.browser.title)
        render_time_a = self.browser.find_element_by_css_selector('#render_time').text

        link = self.browser.find_element_by_css_selector('a[href="/tags/p/"]')
        link.click()
        self.wait.until(lambda browser: "Lare.io - Tags" in browser.title)
        render_time_b = self.browser.find_element_by_css_selector('#render_time').text

        self.browser.get("https://lare.io/tags/p/")
        render_time_c = self.browser.find_element_by_css_selector('#render_time').text

        self.assertTrue(render_time_a != render_time_b != render_time_c)

    def test_initial_lare_functionality_page(self):
        self.browser.get("https://lare.io/")
        self.assertEqual("Lare.io - Home", self.browser.title)
        page_a = self.browser.find_element_by_css_selector('#page').text

        link = self.browser.find_element_by_css_selector('a[href="/tags/p/"]')
        link.click()
        self.wait.until(lambda browser: "Lare.io - Tags" in browser.title)
        page_b = self.browser.find_element_by_css_selector('#page').text

        self.browser.get("https://lare.io/tags/p/")
        page_c = self.browser.find_element_by_css_selector('#page').text

        self.assertTrue(page_a != page_b == page_c)

    def tearDown(self):
        self.browser.close()
