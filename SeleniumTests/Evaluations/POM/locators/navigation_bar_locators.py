from selenium.webdriver.common.by import By

class NavigationBarLocators:
    home_bar_xpath = (By.XPATH, "//a[text()='Home']")
    rooms_bar_xpath = (By.XPATH, "//a[text()='Rooms']")
    my_profile_bar_xpath = (By.XPATH, "//a[text()='My profile']")
    admin_dashboard_bar_xpath = (By.XPATH, "//a[text()='Admin dashboard']")
    log_out_bar_xpath = (By.XPATH, "//a[text()='Log out']")
    login_bar_xpath = (By.XPATH, "//a[text()='Login']")