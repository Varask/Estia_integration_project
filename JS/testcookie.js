import  { CookieHandler }  from  './cookieHandler.js' ;

ch = CookieHandler();

ch.generateCookie("test", "test");
ch.generateCookie("connexionID", "1234567890");
ch.printCookies();
ch.send_to_printer_div();

