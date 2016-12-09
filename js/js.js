function max(a, b) {
    if(a > b) return a
    else return b;
}

var first = 1,
    second = 5,
    x = max(first, second);
document.getElementById("filler").innerHTML += x;