import os
import unittest
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver import Keys
from webdriver_manager.chrome import ChromeDriverManager
from POM.helpers import ConfReader


class ConfigTests(unittest.TestCase):

    def init_driver(self):
        self.options = Options()
        self.options.add_argument("start-maximized")
        self.driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=self.options)
        self.driver.implicitly_wait(10)
        return self.driver

    def driver_quit(self):
        self.driver.close()
        self.driver.quit()
