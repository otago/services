export default ({ authenticate }) => {
    const token = new URLSearchParams(window.location.search).get("token");
    if (!token) {
        window.location.href = `${authenticate}?BackURL=${window.location.href}`;
        return false;
    }
    return token;
};
