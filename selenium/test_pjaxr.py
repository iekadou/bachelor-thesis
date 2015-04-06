import unittest

from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.wait import WebDriverWait


class PjaxrFunctionalityTestCase(unittest.TestCase):
    def setUp(self):
        self.browser = webdriver.Firefox()
        self.wait = WebDriverWait(self.browser, 5, poll_frequency=0.1)

    def test_initial_pjaxr_functionality_additional_ids(self):
        self.browser.get("https://pjaxr.io/")
        self.assertEqual("Pjaxr.io - Home", self.browser.title)
        render_time_a = self.browser.find_element_by_css_selector('#render_time').text
        random_tag_a = self.browser.find_element_by_css_selector('#random_tag').text

        link = self.browser.find_element_by_css_selector('a[href="/tags/p/"]')
        link.click()
        self.wait.until(lambda browser: "Pjaxr.io - Tags" in browser.title)
        render_time_b = self.browser.find_element_by_css_selector('#render_time').text
        random_tag_b = self.browser.find_element_by_css_selector('#random_tag').text

        self.browser.get("https://pjaxr.io/tags/p/")
        render_time_c = self.browser.find_element_by_css_selector('#render_time').text
        random_tag_c = self.browser.find_element_by_css_selector('#random_tag').text

        self.assertTrue(render_time_a != render_time_b != render_time_c)

        # assert that random tag changes on every initial request. (chance for not: 1 / 400k)
        self.assertTrue(random_tag_a == random_tag_b != random_tag_c)

    def test_initial_pjaxr_functionality_page(self):
        self.browser.get("https://pjaxr.io/")
        self.assertEqual("Pjaxr.io - Home", self.browser.title)
        page_a = self.browser.find_element_by_css_selector('#page').text

        link = self.browser.find_element_by_css_selector('a[href="/tags/p/"]')
        link.click()
        self.wait.until(lambda browser: "Pjaxr.io - Tags" in browser.title)
        page_b = self.browser.find_element_by_css_selector('#page').text

        self.browser.get("https://pjaxr.io/tags/p/")
        page_c = self.browser.find_element_by_css_selector('#page').text

        self.assertTrue(page_a != page_b == page_c)

    def tearDown(self):
        self.browser.close()
