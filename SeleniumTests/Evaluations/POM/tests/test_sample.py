import sys, os
sys.path.append(os.path.abspath(os.path.dirname(os.path.dirname(__file__) + "/../../")))
from POM.config_tests import *
from POM.pages.sample_page import SamplePage
import xmlrunner
from POM.helpers import Helpers


class TestSample(ConfigTests):

    @classmethod
    def setUpClass(cls):
        cls.driver = super().init_driver(cls)
        cls.samplePage = SamplePage(cls.driver)

    def test_000_check_page_heading(self):
        samplePage = self.samplePage
        samplePage.open_page() 
 

    @classmethod
    def tearDownClass(cls):
        super().driver_quit(cls)


if __name__ == '__main__':
    unittest.main(testRunner=xmlrunner.XMLTestRunner(output='test-reports'))
