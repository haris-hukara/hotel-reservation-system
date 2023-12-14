# QA Automation - UI Tests
Technologies used: Python and Selenium.

## Installation
---------------------------------------

### Python3
python3

### Python3 pip (package installer for Python)
install python3-pip
pip3 install --upgrade pip

### pip3 packaging
pip3 install packaging

### WebDriver manager
pip3 install webdriver-manager

### Selenium
pip3 install -U selenium

### unittest-xml-reporting
pip install unittest-xml-reporting

### junit2html
pip install junit2html

---------------------------------------


### Running individual test
Each test starts with "test" and it ends with ".py" extension
To run test use following command _( replace test_name with actual name, do not include ".py" extension )_
```bash
python3 -m unittest test_name
```
 
### Running all tests
To run all tests use following command _( "discover" is a keyword, it  will find and run all ".py" files that start with "test" )_
```bash
python3 -m unittest discover
```

### Creating XML reports
To create XML report use following command _( to create XML report for all tests just replace test_name with "discover" keyword )_ 
```bash
 python3 -m xmlrunner test_name -o test-reports
```
"test-reports" is name of directory where reports are placed

### Convert XML reports to HTML
To convert XML report to HTML use following command _( replace "test_file.xml" with name of .xml file you want to convert )_
```bash
junit2html test-reports/test_file.xml
```
You can find HTML report in  directory "test-reports"
