const express = require("express");
const path = require("path");
const ejs = require("ejs");
const { request } = require("express");
const res = require("express/lib/response");
const req = require("express/lib/request");


const app = express();


app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.set("view engine", "ejs");
app.use("/public", express.static(__dirname + "/public"));

//Nav routes
app.get("/", (req, res) => {
    res.render("index");
});

app.get("/views/index.ejs", (req, res) => {
    res.render("index");
});

app.get("/views/html/about.ejs", (req, res) => {
    res.render("./html/about.ejs");
});
app.get("/views/html/Schedule.ejs", (req, res) => {
    res.render("./html/Schedule.ejs");
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server is running on ${PORT}`));
