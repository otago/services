export default (url) => {
    const token = new URLSearchParams(window.location.search).get("token");
    if (!token) {
        window.location.href = `${url}?BackURL=${window.location.href}`;
        return false;
    }
    return token;
};
