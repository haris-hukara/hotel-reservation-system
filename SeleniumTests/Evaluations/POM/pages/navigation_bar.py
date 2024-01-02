from POM.pages.base_page import *
from POM.locators.navigation_bar_locators import NavigationBarLocators


class NavigationBar(NavigationBarLocators):

    def click_home_bar(self):
        self.click(self.home_bar_xpath)

    def click_rooms_bar(self):
        self.click(self.rooms_bar_xpath)

    def click_my_profile_bar(self):
        self.click(self.my_profile_bar_xpath)

    def click_admin_dashboard_bar(self):
        self.click(self.admin_dashboard_bar_xpath)

    def click_log_out_bar(self):
        self.click(self.log_out_bar_xpath)

    def click_login_bar(self):
        self.click(self.login_bar_xpath)

 