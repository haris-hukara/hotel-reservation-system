import sys, os
sys.path.append(os.path.abspath(os.path.dirname(os.path.dirname(__file__) + "/../../")))
from POM.config_tests import *
from POM.pages.rooms_page import RoomsPage
import time
from time import sleep
import xmlrunner
from POM.helpers import Helpers


class TestMakeReservation(ConfigTests):


    @classmethod
    def setUpClass(cls):
        cls.driver = super().init_driver(cls)
        cls.roomsPage = RoomsPage(cls.driver)

    def test_000_check_login_unhappy(self):
        roomsPage = self.roomsPage
        roomsPage.open_page() 
        roomsPage.send_keys(roomsPage.reservations_checkin_input_id, "13.01.2024")
        roomsPage.send_keys(roomsPage.reservations_checkout_input_id, "14.01.2024")
        roomsPage.send_keys(roomsPage.reservations_adults_input_id, "2")
        roomsPage.send_keys(roomsPage.reservations_children_input_id, "2")
        roomsPage.click(roomsPage.check_avaliability_button_id)

    @classmethod
    def tearDownClass(cls):
        super().driver_quit(cls)


if __name__ == '__main__':
    unittest.main(testRunner=xmlrunner.XMLTestRunner(output='test-reports'))
