import sys, os
sys.path.append(os.path.abspath(os.path.dirname(os.path.dirname(__file__) + "/../../")))
from POM.config_tests import *
from POM.pages.login_page import LoginPage
import time
from time import sleep
import xmlrunner
from POM.helpers import Helpers


class TestLogin(ConfigTests):

    error_message = "This field is required."

    @classmethod
    def setUpClass(cls):
        cls.driver = super().init_driver(cls)
        cls.loginPage = LoginPage(cls.driver)

    def test_000_check_login_unhappy(self):
        loginPage = self.loginPage
        loginPage.open_page() 
        loginPage.click(loginPage.login_button_id)
        assert loginPage.get_field_error_message(loginPage.login_form_email_error_xpath) == self.error_message
        assert loginPage.get_field_error_message(loginPage.login_form_password_error_xpath)  == self.error_message

    def test_001_forgot_password_unhappy(self):
        loginPage = self.loginPage
        loginPage.click(loginPage.forgot_password_link_xpath)
        loginPage.click(loginPage.send_recovery_link_button_xpath)
        assert loginPage.get_field_error_message(loginPage.forgot_form_email_error_xpath) == self.error_message
        loginPage.click(loginPage.forgot_form_back_to_login_link_xpath)
        
    def test_002_register_unhappy(self):
        loginPage = self.loginPage
        loginPage.click(loginPage.register_here_link_xpath)
        loginPage.click(loginPage.register_button_id)
        assert loginPage.get_field_error_message(loginPage.register_form_email_error_xpath) == self.error_message
        assert loginPage.get_field_error_message(loginPage.register_form_first_name_error_xpath ) == self.error_message
        assert loginPage.get_field_error_message(loginPage.register_form_password_error_xpath) == self.error_message
        assert loginPage.get_field_error_message(loginPage.register_form_last_name_error_xpath ) == self.error_message
        assert loginPage.get_field_error_message(loginPage.register_form_birth_date_error_xpath ) == self.error_message
        assert loginPage.get_field_error_message(loginPage.register_form_country_error_xpath ) == self.error_message
        assert loginPage.get_field_error_message(loginPage.register_form_city_error_xpath) == self.error_message
        loginPage.click(loginPage.register_form_back_to_login_link_xpath)

    def test_003_check_login(self):
        loginPage = self.loginPage
        loginPage.send_keys(loginPage.email_textbox_name, loginPage.email)
        loginPage.send_keys(loginPage.password_textbox_name, loginPage.password)
        loginPage.click(loginPage.login_button_id)
        sleep(2)
        assert loginPage.get_current_url() == "http://localhost/hotelsea/index.html#homepage"

    def test_004_check_logout(self):
        loginPage = self.loginPage
        loginPage.click_log_out_bar()
 

    @classmethod
    def tearDownClass(cls):
        super().driver_quit(cls)


if __name__ == '__main__':
    unittest.main(testRunner=xmlrunner.XMLTestRunner(output='test-reports'))
