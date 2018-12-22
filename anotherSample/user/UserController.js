var express = require('express');
var router = express.Router();
var bodyParser = require('body-parser');

router.use(bodyParser.urlencoded({ extended: true }));
router.use(bodyParser.json());
var User = require('./User');

// CREATES A NEW USER
router.post('/add_user', function (req, res) {
    console.log(req.body.username);

    User.create({
            username : req.body.username,
            email : req.body.email,
            password : req.body.password
        }, 
        function (err, user) {
            if (err) return res.status(500).send("no");
            console.log(user);
            res.status(200).send(user);
        });
});

// RETURNS THE USERS IN THE DATABASE
router.get('/user/:username', function (req, res) {   
    console.log(req.params.username);
    User.findOne({'username':req.params.username}, function (err, user) {

        console.log(user);
        if (err) return res.status(500).send("no");       
         res.status(200).send(user);         
         
    });
  
    console.log(__dirname);
});

// GETS ALL USER FROM THE DATABASE
router.get('/users', function (req, res) {
    User.find({}, function (err, user) {
        if (err) return res.status(500).send("There was a problem finding the user.");
        if (!user) return res.status(404).send("No user found.");
        res.status(200).send(user);
    });
});

//DELETES A USER FROM THE DATABASE
router.delete('/users/:id', function (req, res) {
    User.findByIdAndRemove({'_id':req.params.id}, function (err, user) {
        if (err) return res.status(500).send("There was a problem deleting the user.");
        res.status(200).send("User: "+ user.name +" was deleted.");
    });
});

// // UPDATES A SINGLE USER IN THE DATABASE
// router.put('/:id', function (req, res) {
//     User.findByIdAndUpdate(req.params.id, req.body, {new: true}, function (err, user) {
//         if (err) return res.status(500).send("There was a problem updating the user.");
//         res.status(200).send(user);
//     });
// });


module.exports = router;