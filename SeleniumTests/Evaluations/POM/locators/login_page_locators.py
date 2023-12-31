from selenium.webdriver.common.by import By

class LoginPagePageLocators:
    email_textbox_name = (By.NAME, "email")
    password_textbox_name = (By.NAME, "password")
    login_button_id = (By.ID, "login-link")
