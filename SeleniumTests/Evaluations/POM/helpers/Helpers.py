import sys
# , os
# sys.path.append(os.path.abspath(os.path.dirname(os.path.dirname(__file__) + "/../../")))
from time import time
from datetime import datetime

date_format_reporting_page = '%Y-%m-%d %H:%M'
date_format_recieved = '%Y-%m-%d'
date_format_desired = '%d.%m.%Y.'
date_format_management_page = '%d.%m.%Y %H:%M'


def get_current_timestamp_as_string():
    return str(int(time()))


def get_timestamp_as_string_by_day_difference(timestamp, days_difference):
    timestamp = int(timestamp)
    SECOND = 1
    MINUTE = 60 * SECOND
    HOUR = 60 * MINUTE
    DAY = 24 * HOUR
    output = timestamp + (DAY * int(days_difference))
    return str(output)


def convert_timestamp_to_datetime_string(timestamp, date_format):
    datetime_string = str(datetime.fromtimestamp(int(timestamp)).strftime(date_format))
    return datetime_string


def convert_datetime_to_timestamp_string(datetime_value, date_format):
    datetime_value = datetime.strptime(str(datetime_value), date_format)
    timestamp_string = str(int(datetime.timestamp(datetime_value)))
    return timestamp_string


def convert_datetime(datetime_value, current_date_format, desired_date_format):
    timestamp = convert_datetime_to_timestamp_string(datetime_value, current_date_format)
    return convert_timestamp_to_datetime_string(timestamp, desired_date_format)

def default_datetime_conversion(datetime_value):
    return convert_datetime(datetime_value, date_format_recieved, date_format_desired)