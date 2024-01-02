import os
from time import sleep
from selenium.common import NoSuchElementException
from selenium.webdriver import Keys
from selenium.webdriver import Keys, ActionChains
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, ElementNotInteractableException, ElementClickInterceptedException
from selenium.webdriver.support.wait import WebDriverWait
from POM.helpers import ConfReader
from POM.pages.navigation_bar import *

class BasePage(NavigationBar):
    configurations = os.path.join(os.path.abspath(os.path.dirname(os.path.dirname(__file__) + "/../tests/")),
                                  'tests.env')
    email = ConfReader.get_value(configurations, 'LOGIN_EMAIL')
    password = ConfReader.get_value(configurations, 'LOGIN_PASSWORD')
    base_url = ConfReader.get_value(configurations, 'BASE_URL')

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(self.driver, 10)

    def open_page(self):
        self.driver.get(self.base_url)
        sleep(3)

    def open_page_in_new_tab(self):
        self.open_new_tab()
        sleep(1)
        self.open_page()

    def get_window_handles(self):
        return self.driver.window_handles

    def get_current_window(self):
        return self.driver.current_window_handle

    def get_window_by_window_id(self, window_id):
        return self.get_window_handles()[window_id - 1]

    def open_new_tab(self):
        self.driver.switch_to.new_window("tab")

    def switch_to_window(self, window):
        self.driver.switch_to.window(window)
        sleep(1)

    def switch_to_tab_by_id(self, tab_id):
        self.switch_to_window(self.get_window_by_window_id(tab_id))

    def close_current_tab(self):
        self.driver.close()

    def close_tab_by_id(self, tab_id):
        self.switch_to_tab_by_id(tab_id)
        self.close_current_tab()

    def element_is_available(self, locator, implicit_wait=None):
        try:
            if implicit_wait:
                self.driver.implicitly_wait(implicit_wait)
            else:
                self.driver.implicitly_wait(3)

            self.driver.find_element(*locator)
        except NoSuchElementException:
            self.driver.implicitly_wait(10)
            return False
        self.driver.implicitly_wait(10)
        return True

    def get_element(self, locator):
        try:
            element_present = EC.presence_of_element_located(locator)
            return self.wait.until(element_present)
        except TimeoutException:
            raise TimeoutException(
                "TimeoutException: Element not found on page, used selector: By." + locator[0] + "(\"" + locator[
                    1] + "\")")

    def get_element_text(self, locator):
        return self.get_element(locator).text

    def get_title(self):
        return self.driver.title

    def click(self, locator):
        try:
            element_present = EC.presence_of_element_located(locator)
            self.wait.until(element_present).click()
        except ElementClickInterceptedException:
            element = self.wait.until(EC.element_to_be_clickable(locator))
            element.click();
        except TimeoutException:
            raise TimeoutException(
                "TimeoutException: Element not found on page, used selector: By." + locator[0] + "(\"" + locator[
                    1] + "\")")
        except ElementNotInteractableException:
            try:
                element_present = EC.presence_of_element_located(locator)
                element = self.wait.until(element_present)
                ActionChains(self.driver).move_to_element(element).click(element).perform()
            except ElementNotInteractableException:
                raise ElementNotInteractableException(
                    "ElementNotInteractableException: Element is not interactable, used selector: By." + locator[
                        0] + "(\"" + locator[1] + "\")")

    def send_keys(self, locator, text):
        try:
            element_present = EC.presence_of_element_located(locator)
            print(str(self.wait.until(element_present).get_attribute("value")))
            if str(self.wait.until(element_present).get_attribute("value")) != "":
                self.wait.until(element_present).send_keys(Keys.CONTROL + "a" + Keys.DELETE)
                self.wait.until(element_present).clear()
            self.wait.until(element_present).send_keys(text)

        except TimeoutException:
            raise TimeoutException("TimeoutException: Element not found on page, used selector: By." + locator[0] + "(\"" + locator[1] + "\")")

    def get_current_url(self):
        return self.driver.current_url
