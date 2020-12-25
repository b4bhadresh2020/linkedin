<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Linkedin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LinkedinController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Faker $Faker)
    {
        $this->middleware('auth');
        $this->faker = $Faker;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $linkedinUsers = Linkedin::paginate(10);
        return view("home",compact("linkedinUsers"));
    }

    public function create(){
        // create your browser session
        $serverUrl = 'http://localhost:4444';

        // Create an instance of ChromeOptions:
        $options = new ChromeOptions();
        $options->addArguments(['--headless']);
        $options->addArguments(['start-maximized']);

        // Create $capabilitites and start new browser instance with configuration from ChromeOptions
        $capabilitites = DesiredCapabilities::chrome();
        $capabilitites->setCapability(ChromeOptions::CAPABILITY_W3C, $options);
        $driver = RemoteWebDriver::create($serverUrl, $capabilitites);

        // Go to Linkdin registration URL
        $driver->get('https://www.linkedin.com/signup/cold-join?source=guest_homepage-basic_nav-header-signin');

        //user data

        $fullName   = $this->faker->name;
        $firstName  = substr($fullName,0, strrpos($fullName,' '));
        $lastName   = substr($fullName, (strrpos($fullName,' ') + 1));

        $userData = array(
            'uuid'      =>  Str::uuid(),
            'email'     => $this->faker->safeEmail,
            'password'  => Str::random(10),
            'first_name'=> $firstName,
            'last_name' => $lastName
        );

        // Create a directory for the particular UUID
        $directoryPath = public_path('storage/linkedin/'.$userData['uuid']);
        File::makeDirectory($directoryPath, $mode = 0777, true, true);

        // Fill the registration form
        $driver->findElement(WebDriverBy::id('email-or-phone'))->sendKeys($userData['email']); // fill the email box
        $driver->findElement(WebDriverBy::id('password'))->sendKeys($userData['password']); // fill the password box

        // Take a screenshot
        $screenshotFileName = $directoryPath.'/screenshot_1.png';
        $driver->takeScreenshot($screenshotFileName);

        // submit
        $submitButton = $driver->findElement(WebDriverBy::id('join-form-submit'));
        $submitButton->click();

        // Wait untill we get first_name element find from page
        $firstNameElement = $driver->findElement(WebDriverBy::id('first-name'));
        $driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOf($firstNameElement));

        $driver->findElement(WebDriverBy::id('first-name'))->sendKeys($userData['first_name']); // fill the first-name box
        $driver->findElement(WebDriverBy::id('last-name'))->sendKeys($userData['last_name']); // fill the last-name box

        // Take a screenshot
        $screenshotFileName = $directoryPath.'/screenshot_2.png';
        $driver->takeScreenshot($screenshotFileName);

        //submit
        $submitButton = $driver->findElement(WebDriverBy::id('join-form-submit'));
        $submitButton->click();

        // wait 2 seconds because here verification popup is open.
        sleep(2);

        // Take a screenshot
        $screenshotFileName = $directoryPath.'/screenshot_3.png';
        $driver->takeScreenshot($screenshotFileName);
        $driver->quit();

        // save userdata in linkdedin schema.
        Linkedin::create($userData);

        return redirect('/linkedin');
    }

    public function show($uuid){
        $directoryPath = public_path('storage/linkedin/'.$uuid);
        $images = File::files($directoryPath);
        return view("screenshot",compact("uuid","images"));
    }
}
