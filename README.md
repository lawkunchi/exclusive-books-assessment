# Lawrence Chibondo

This application displays a line chat with total number of coronavirus cases and deaths in South Africa 

## Installation

1. Clone this repo to your dev enviroment(for a symfony application)

2. Navigate to the root folder of this repo and run `composer install` to install application dependencies

3. Do the following to install the JavaScript dependencies as well and compile

  `yarn install --force` or `npm install --force` (if you prefer npm)
 
  `yarn encore dev` or `npm run dev` (if you prefer npm)

4. Lastly run `php bin/console server:run` to start application, visit [http://127.0.0.1:8000/](http://127.0.0.1:8000/) to explore the line chart data