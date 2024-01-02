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

        check_in = "13.01.2024."
        check_out = "14.01.2024."
        adults = "2"
        children = "2"

        roomsPage.send_keys(roomsPage.reservations_checkin_input_id, check_in)
        roomsPage.send_keys(roomsPage.reservations_checkout_input_id, check_out)
        roomsPage.send_keys(roomsPage.reservations_adults_input_id, adults)
        roomsPage.send_keys(roomsPage.reservations_children_input_id, children)
        roomsPage.click(roomsPage.check_avaliability_button_id)
        sleep(1)
        room_price = roomsPage.get_element_text(roomsPage.room_one_price_id)
        room_name = roomsPage.get_element_text(roomsPage.room_one_name)

        roomsPage.click(roomsPage.make_reservation_button_xpath)
        sleep(3)

        checkin_converted = Helpers.default_datetime_conversion(roomsPage.get_element_value_string(roomsPage.reservations_modal_room_checkin_input_id))
        checkout_converted = Helpers.default_datetime_conversion(roomsPage.get_element_value_string(roomsPage.reservations_modal_room_checkout_input_id))

        assert check_in == checkin_converted
        assert check_out == checkout_converted
        assert adults ==  roomsPage.get_element_value_string(roomsPage.reservations_modal_room_adults_input_id)
        assert children ==  roomsPage.get_element_value_string(roomsPage.reservations_modal_room_children_input_id)
        

    @classmethod
    def tearDownClass(cls):
        super().driver_quit(cls)


if __name__ == '__main__':
    unittest.main(testRunner=xmlrunner.XMLTestRunner(output='test-reports'))
