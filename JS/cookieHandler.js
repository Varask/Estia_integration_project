class CookieHandler {
    constructor() {
        this.cookies = {};
        this.loadCookies();
        this.identifier = "_LTD_PROJECT"
    }
    
    getCookiesWithIdentifier() {
        var cookies = document.cookie;
        var cookiesObj = {};
    
        cookies.split(';').forEach(function(cookie) {
            var parts = cookie.split('=');
            var key = parts[0].trim();
            if (key.includes(this.identifier)) { 
                cookiesObj[key] = decodeURIComponent(parts[1] || '');
            }
        });
    
        return cookiesObj;
    }

    loadCookies() {
        this.cookies = this.getCookiesWithIdentifier();
    }

    generateCookie(key, value) {
        var d = new Date();
        d.setTime(d.getTime() + (365*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = key+this.identifier + "=" + value + ";" + expires + ";path=/";
    }

    printCookies() {
        console.log(this.cookies);
    }

    printCookieToString() {
        var str = "";
        for (var key in this.cookies) {
            str += key + "=" + this.cookies[key] + "; \n";
        }
        return str;
    }

    send_to_printer_div() {
        var str = this.printCookieToString();
        document.getElementById("printer").innerHTML = str;
    }
}