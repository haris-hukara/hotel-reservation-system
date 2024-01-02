from POM.pages.base_page import *
from POM.locators.rooms_page_locators import RoomsPagePageLocators


class RoomsPage(RoomsPagePageLocators, BasePage):
    base_url = "http://" + BasePage.base_url + "/hotelsea/index.html#rooms"

    def login(self, email, password):
        self.send_keys(self.email_textbox_name, email)
        self.send_keys(self.password_textbox_name, password)
        self.click(self.login_button_id)

    def get_field_error_message(self,locator):
        return self.get_element_text(locator)