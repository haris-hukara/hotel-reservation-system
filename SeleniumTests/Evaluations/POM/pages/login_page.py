from POM.pages.base_page import *
from POM.locators.login_page_locators import LoginPagePageLocators


class LoginPage(LoginPagePageLocators, BasePage):
    base_url = "http://" + BasePage.base_url + "/hotelsea/login.html"




