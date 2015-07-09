import time
import unittest

from selenium import webdriver
from selenium.webdriver.support.wait import WebDriverWait


class LareFunctionalityTestCase(unittest.TestCase):
    def setUp(self):
        self.browser = webdriver.Firefox()
        self.wait = WebDriverWait(self.browser, 5, poll_frequency=0.1)
        self.maxDiff = None

    def test_lare_content(self):
        self.browser.get("https://lare.io/")
        self.assertEqual("Lare.io - Home", self.browser.title)
        self.assertEquals("https://lare.io/", self.browser.current_url)

        content_home_normal = self.browser.page_source

        link = self.browser.find_element_by_css_selector('a[href="/tags/p/"]')
        link.click()
        self.wait.until(lambda browser: "Lare.io - Tags" in browser.title)
        self.assertEquals("https://lare.io/tags/p/", self.browser.current_url)
        time.sleep(5)

        content_tags_lare = self.browser.page_source

        self.browser.get("https://lare.io/tags/p/")
        self.wait.until(lambda browser: "Lare.io - Tags" in browser.title)
        self.assertEquals("https://lare.io/tags/p/", self.browser.current_url)
        time.sleep(5)

        content_tags_normal = self.browser.page_source

        self.assertEquals(content_tags_normal, content_tags_lare)

        self.browser.back()
        self.assertEqual("Lare.io - Home", self.browser.title)
        self.assertEquals("https://lare.io/", self.browser.current_url)
        time.sleep(5)

        content_home_lare_back = self.browser.page_source

        self.assertEquals(content_home_normal, content_home_lare_back)

        self.browser.forward()
        self.wait.until(lambda browser: "Lare.io - Tags" in browser.title)
        self.assertEquals("https://lare.io/tags/p/", self.browser.current_url)
        time.sleep(5)

        content_home_lare_forward = self.browser.page_source

        self.assertEquals(content_tags_normal, content_home_lare_forward)

    def tearDown(self):
        self.browser.close()
