import sys, os
sys.path.append(os.path.abspath(os.path.dirname(os.path.dirname(__file__) + "/../../")))
from POM.config_tests import *
from POM.pages.login_page import LoginPage
import time
from time import sleep
import xmlrunner
from POM.helpers import Helpers


class TestLogin(ConfigTests):

    @classmethod
    def setUpClass(cls):
        cls.driver = super().init_driver(cls)
        cls.loginPage = LoginPage(cls.driver)

    def test_000_check_login(self):
        loginPage = self.loginPage
        loginPage.open_page() 
        loginPage.send_keys(loginPage.email_textbox_name, loginPage.email)
        loginPage.send_keys(loginPage.password_textbox_name, loginPage.password)
        loginPage.click(loginPage.login_button_id)
        sleep(3)
        assert loginPage.get_current_url() == "http://localhost/hotelsea/index.html#homepage"

    def test_001_check_logout(self):
        loginPage = self.loginPage
        loginPage.click_log_out_bar()
 

    @classmethod
    def tearDownClass(cls):
        super().driver_quit(cls)


if __name__ == '__main__':
    unittest.main(testRunner=xmlrunner.XMLTestRunner(output='test-reports'))
