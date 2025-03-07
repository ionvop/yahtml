// set document title to name of current directory
let url = window.location.pathname;
url = url.substring(0, url.lastIndexOf("/"));
url = url.substring(url.lastIndexOf("/") + 1);

if (url == "") {
    url = "home";
}

url = url.charAt(0).toUpperCase() + url.slice(1);
document.title = url;