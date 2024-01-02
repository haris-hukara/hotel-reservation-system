from POM.pages.base_page import *
from POM.locators.login_page_locators import LoginPagePageLocators


class LoginPage(LoginPagePageLocators, BasePage):
    base_url = "http://" + BasePage.base_url + "/hotelsea/login.html"


    def login(self, email, password):
        self.send_keys(self.email_textbox_name, email)
        self.send_keys(self.password_textbox_name, password)
        self.click(self.login_button_id)

