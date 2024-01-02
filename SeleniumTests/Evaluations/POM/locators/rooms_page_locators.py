from selenium.webdriver.common.by import By

class RoomsPagePageLocators:
    reservations_checkin_input_id = (By.ID, "reservations-check-in")
    reservations_checkout_input_id = (By.ID, "reservations-check-out")
    reservations_adults_input_id = (By.ID, "reservations-adults")
    reservations_children_input_id = (By.ID, "reservations-children")
    check_avaliability_button_id = (By.ID, "reservations-button")
   
    make_reservation_button_xpath = (By.XPATH, "//*[@id='rooms-box']/div[1]/div/div[2]/button")
    room_one_name = (By.ID, "room-1-name")
    room_one_price_id = (By.ID, "room-1-price")

    reservations_modal_room_id_input_id = (By.ID, "modal-room-id")
    reservations_modal_room_price_input_id = (By.ID, "modal-room-price")
    reservations_modal_room_checkin_input_id = (By.ID, "modal-room-check-in")
    reservations_modal_room_checkout_input_id = (By.ID, "modal-room-check-out")
    reservations_modal_room_adults_input_id = (By.ID, "modal-room-adults")
    reservations_modal_room_children_input_id = (By.ID, "modal-room-children")

