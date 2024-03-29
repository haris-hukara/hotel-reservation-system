"""
A simple conf reader.
For now, we just use dotenv and return a key.
"""

import dotenv
import os


def get_value(conf, key):
    "Return the value in conf for a given key"
    value = None
    try:
        dotenv.load_dotenv(conf)
        value = os.environ[key]
    except Exception as e:
        print('Exception in get_value')
        print('file: ', conf)
        print('key: ', key)

    return value
