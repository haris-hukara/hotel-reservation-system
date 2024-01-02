from selenium.webdriver.common.by import By

class LoginPagePageLocators:
    email_textbox_name = (By.NAME, "email")
    password_textbox_name = (By.NAME, "password")
    login_button_id = (By.ID, "login-link")
    register_button_id = (By.ID, "register-link")
    send_recovery_link_button_xpath = (By.XPATH, "//*[@id='forgot-form']/button[text()='Send recovery link']")

    forgot_password_link_xpath = (By.XPATH, "//a[text()='Forgot Password? ']")
    forgot_form_back_to_login_link_xpath = (By.XPATH, "//*[@id='forgot-form']/div/a[contains(text(), 'Back to login')]")
    change_password_form_back_to_login_link_xpath = (By.XPATH, "//*[@id='change-password-form']/div/a[contains(text(), 'Back to login')]")
    register_form_back_to_login_link_xpath = (By.XPATH, "//*[@id='register-form']/div/a[contains(text(), 'Back to login')]")
    register_here_link_xpath = (By.XPATH, "//*[@id='login-form']/div/a[contains(text(), 'Register Here')]")
    
    forgot_form_email_error_xpath = (By.XPATH, "//*[@id='forgot-form']//*[@id='email-error']")
    
    login_form_email_error_xpath = (By.XPATH, "//*[@id='login-form']//*[@id='email-error']")
    login_form_password_error_xpath = (By.XPATH, "//*[@id='login-form']//*[@id='password-error']")
    
    register_form_email_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='email-error']")
    register_form_password_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='password-error']")
    register_form_first_name_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='first_name-error']")
    register_form_last_name_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='last_name-error']")
    register_form_birth_date_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='birth_date-error']")
    register_form_country_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='country-error']")
    register_form_city_error_xpath = (By.XPATH, "//*[@id='register-form']//*[@id='city-error']")