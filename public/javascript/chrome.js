function getToken() {
    time = new Date();
    
    return time.getSeconds()+time.getMilliseconds();
}