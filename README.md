
# **Laravel Application & Razorpay Payment Demo**

This project is a full-stack application featuring user registration, an application form with validation and file upload, and integration with the Razorpay payment gateway, all secured by a protected dashboard.

## 🚀 **1. Setup and Installation**

Follow these steps from your project's root directory:

1.  ### **Install Dependencies**

Install all necessary PHP packages and compile the front-end assets:

  

- Install PHP dependencies (including Breeze and Razorpay SDK) composer install

- Install and compile front-end assets (required by Laravel Breeze) npm install

  

npm run dev

2.  ### **Database Setup**

Ensure your database is configured in the .env file, then set up the tables and storage link:

  

- Run migrations to create 'users' and 'applications' tables php artisan migrate

- Create the storage link for uploaded resumes php artisan storage:link

## 🔑 **2. Environment Configuration (.env)**

Verify these crucial settings in your project's .env file.

  

  

|**Variable** |**Description** |**Value** |
| - | - | - |
|**APP\_URL** |The local server address for route generation. |http://localhost:8000 |
|**RAZORPAY\_KEY\_ID** |Your Razorpay **Test** Key ID. |rzp\_test\_XXXXXXXXXXXXXX XX |
| - | - | :- |
|**RAZORPAY\_KEY\_SECRET** |Your Razorpay **Test** Key Secret. |YYYYYYYYYYYYYYYYYYYYYY YY |

### 💡 **Clear Configuration Cache**

If you change any of the above keys, you **must** clear the cache: php artisan config:clear

## 🏃 **3. Running the Project**

1.  ### **Start the Server**

Start the standard Laravel development server in your terminal: 
```php artisan serve```

2.  ### **Access Key URLs**

Access the application using the following URLs (assuming the server is running on port 8000):

  
  
  

|**Component** |**URL Path** |**Purpose** |
| - | - | - |
|**Application Form** |http://localhost:8000/ |Start the application process. |
|**Registration** |http://localhost:8000/regist er |Create a new user account. |
|**Login** |http://localhost:8000/login |Log in to access the dashboard. |
|**Dashboard** |http://localhost:8000/dash board |View application and payment data. |

## 🧪 **4. Adding Demo Data**

To quickly verify that your dashboard display logic is working, run the database seeder: php artisan db:seed

  

This command inserts **20 test applications** with varied statuses (paid, pending, failed) into your database.


NOTE : If the server 500 error is thrown it points that the razorpay key you entered has expired
