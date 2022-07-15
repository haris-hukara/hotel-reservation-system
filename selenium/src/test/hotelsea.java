package test;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.Assert;
import org.junit.jupiter.api.AfterAll;
import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Order;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.junit.jupiter.api.TestMethodOrder;
import org.junit.jupiter.api.MethodOrderer.OrderAnnotation;

@TestMethodOrder(OrderAnnotation.class)
class hotelsea {

	private static WebDriver webDriver;
	private static String baseUrl;
	
	@BeforeAll
	static void setUp() throws InterruptedException {
		System.setProperty("webdriver.chrome.driver", "C:\\selenium\\chromedriver.exe");
		webDriver = new ChromeDriver();
		baseUrl = "http://localhost/hotelsea/";
		
		webDriver.manage().window().maximize();
		webDriver.get(baseUrl);
		Thread.sleep(3000);
	}
	
	@BeforeEach
	 void beforeEach() throws InterruptedException {
		baseUrl = "http://localhost/hotelsea/";
		webDriver.manage().window().maximize();
		webDriver.get(baseUrl);
		Thread.sleep(1000);
	}
	@Test
	@Order(1)
	void testHomepageCheckAvaliability() throws InterruptedException {
		
		Thread.sleep(2000);
		
		WebElement checkAvaliability =
		webDriver.findElement(By.xpath("//*[@id=\"home-filters\"]/div[4]/a"));
		
		
		WebElement checkInDate =  webDriver.findElement(By.xpath("//*[@id=\"home-filters\"]/div[1]/div/input"));
		WebElement checkOutDate =  webDriver.findElement(By.xpath("//*[@id=\"home-filters\"]/div[2]/div/input"));
		
		WebElement roomsCount =  webDriver.findElement(By.xpath("//*[@id=\"home-filters\"]/div[3]/div[1]/input"));
		WebElement adultsCount =  webDriver.findElement(By.xpath("//*[@id=\"home-filters\"]/div[3]/div[2]/input"));
		WebElement childrenCount =  webDriver.findElement(By.xpath("//*[@id=\"home-filters\"]/div[3]/div[3]/input"));
			
		roomsCount.sendKeys("1");
		adultsCount.sendKeys("1");
		childrenCount.sendKeys("1");
		checkInDate.sendKeys("01-01-2022");
		checkOutDate.sendKeys("01-31-2022");
		Thread.sleep(2000);
		checkAvaliability.click();
			
		String currentURL = webDriver.getCurrentUrl();
		Assert.assertEquals(currentURL, "http://localhost/hotelsea/#rooms" );
		Thread.sleep(3000);

		WebElement room1 =  webDriver.findElement(By.xpath("//*[@id=\"rooms-box\"]/div[1]/div/div[1]/div[1]/h2"));
		WebElement room2 =  webDriver.findElement(By.xpath("//*[@id=\"rooms-box\"]/div[2]/div/div[1]/div[1]/h2"));
		WebElement room3 =  webDriver.findElement(By.xpath("//*[@id=\"rooms-box\"]/div[3]/div/div[1]/div[1]/h2"));
		
		Assert.assertEquals(room1.getText(), "Standard Room");
		Assert.assertEquals(room2.getText(), "Standard Room 2");
		Assert.assertEquals(room3.getText(), "Standard Room 3");
		Thread.sleep(2000);
	}

	@Test
	@Order(2)
	void testNavigation() throws InterruptedException {
		WebElement navRooms =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[2]/a"));
		navRooms.click();
		Thread.sleep(1000);
		Assert.assertEquals(webDriver.getCurrentUrl(), "http://localhost/hotelsea/#rooms" );
		
		WebElement navHome =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[1]/a"));
		navHome.click();
		Thread.sleep(1000);
		Assert.assertEquals(webDriver.getCurrentUrl(), "http://localhost/hotelsea/#homepage" );
		
		WebElement navLogin =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[3]/a"));
		navLogin.click();
		Thread.sleep(1000);
		Assert.assertEquals(webDriver.getCurrentUrl(), "http://localhost/hotelsea/login.html" );
		
		Thread.sleep(1000);
	}
	@Test
	@Order(3)
	void testLoginPage() throws InterruptedException {
		WebElement navLogin =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[3]/a"));
		navLogin.click();
		Thread.sleep(1000);
		
		WebElement loginForm =  webDriver.findElement(By.id("login-form-container"));
		WebElement registerForm =  webDriver.findElement(By.id("register-form-container"));
		WebElement forgotForm =  webDriver.findElement(By.id("forgot-form-container"));
		WebElement changePasswordForm =  webDriver.findElement(By.id("change-password-form-container"));
		
		Assert.assertEquals(false, elementHasClass(loginForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(registerForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(forgotForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(changePasswordForm, "hidden"));
		
		WebElement forgotPasswordLink =  webDriver.findElement(By.xpath("//*[@id=\"login-form\"]/div[5]/a[1]"));
		WebElement registerLink =  webDriver.findElement(By.xpath("//*[@id=\"login-form\"]/div[5]/a[2]"));
		
		forgotPasswordLink.click();	
		Assert.assertEquals(true, elementHasClass(loginForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(registerForm, "hidden"));
		Assert.assertEquals(false, elementHasClass(forgotForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(changePasswordForm, "hidden"));
		
		
		WebElement forogtPasswordBackToLogin =  webDriver.findElement(By.xpath("//*[@id=\"forgot-form\"]/div[3]/a"));
		forogtPasswordBackToLogin.click();
		registerLink.click();

		Assert.assertEquals(true, elementHasClass(loginForm, "hidden"));
		Assert.assertEquals(false, elementHasClass(registerForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(forgotForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(changePasswordForm, "hidden"));

		
		webDriver.navigate().to(baseUrl + "login.html?token=1");
		Thread.sleep(1000);
		loginForm =  webDriver.findElement(By.id("login-form-container"));
		registerForm =  webDriver.findElement(By.id("register-form-container"));
		forgotForm =  webDriver.findElement(By.id("forgot-form-container"));
		changePasswordForm =  webDriver.findElement(By.id("change-password-form-container"));

		Assert.assertEquals(true, elementHasClass(loginForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(registerForm, "hidden"));
		Assert.assertEquals(true, elementHasClass(forgotForm, "hidden"));
		Assert.assertEquals(false, elementHasClass(changePasswordForm, "hidden"));
				
		}

	@Test
	@Order(4)
	void testLoginPositive() throws InterruptedException {
		WebElement navLogin =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[3]/a"));
		navLogin.click();
		Thread.sleep(1000);
		
		WebElement emailField=  webDriver.findElement(By.xpath("//*[@id=\"login-form\"]/div[2]/input"));
		WebElement passwordField =  webDriver.findElement(By.xpath("//*[@id=\"login-form\"]/div[4]/input"));
		WebElement loginButton =  webDriver.findElement(By.xpath("//*[@id=\"login-link\"]"));
		
		emailField.sendKeys("haris.hukara@stu.ibu.edu.ba");
		passwordField.sendKeys("password");
		
		loginButton.click();

		Thread.sleep(2000);
		WebElement navMyProfile =  webDriver.findElement(By.xpath("//*[@id=\"about-link\"]"));
		WebElement logOutBtn =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[4]/a"));
		
		
	
		Assert.assertEquals(webDriver.getCurrentUrl(), "http://localhost/hotelsea/index.html#homepage" );
		Assert.assertEquals(navMyProfile.getText(), "My profile");
		Assert.assertEquals(logOutBtn.getText(), "Log out");
		}
	
	@Test
	@Order(5)
	void testLoginNegative() throws InterruptedException {
		WebElement navLogin =  webDriver.findElement(By.xpath("/html/body/header/nav/ul/li[3]/a"));
		navLogin.click();
		Thread.sleep(1000);
		
		
		WebElement emailField=  webDriver.findElement(By.xpath("//*[@id=\"login-form\"]/div[2]/input"));
		WebElement passwordField =  webDriver.findElement(By.xpath("//*[@id=\"login-form\"]/div[4]/input"));
		WebElement loginButton =  webDriver.findElement(By.xpath("//*[@id=\"login-link\"]"));
		
		emailField.sendKeys("unknown@email.ba");
		passwordField.sendKeys("password");
		
		loginButton.click();
		Thread.sleep(1000);
		WebElement toastrMsg =  webDriver.findElement(By.xpath("//*[@id=\"toast-container\"]/div/div[2]"));
		Assert.assertEquals(toastrMsg.getText(), "User doesn't exist");
		
		Thread.sleep(2000);
	}
	
	
	
	
	@AfterAll
	static void tearDown() throws InterruptedException {
		Thread.sleep(5000);
		webDriver.close();
	}
	
	
	public boolean elementHasClass(WebElement element, String active) {
	    return element.getAttribute("class").contains(active);
	}
}
