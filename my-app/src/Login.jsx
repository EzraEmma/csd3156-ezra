/*!************************************************************************
 * \file Login.jsx
 * \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for Login Page.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/

import React, { useState, useEffect } from "react";
// import { useRef } from 'react';
import { Box, TextField, Button, Paper } from "@mui/material";
import sofaLogo from "./assets/sofasogoodicon.png";
import { Link, useLocation } from "wouter";
import "./style/Login.css";
import { PHP_URL } from "./AppInclude.jsx";
import axios from "axios";

const Login = () => {
  const [failedLogin, setFailedLogin] = useState(false);
  const [userName, setUserName] = useState("");
  const [password, setPassword] = useState("");
  const [, setLocation] = useLocation();

  const handleLogin = () => {
    // if fail
    // setFailedLogin(true);

    axios
      .post(
        `${PHP_URL}/PostLoginInfo.php`,
        {
          Username: userName,
          PW: password,
        },
        {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
        }
      )
      .then(function (response) {
        console.log(response);

        if (response.data.success) {
          // setFailedLogin(true);
          console.log(response.data.ID[0]);
          sessionStorage.setItem("persistedId", response.data.ID[0]);
          setLocation(`/Catalogue/${response.data.ID[0]}`);
        } else {
          setFailedLogin(false);
        }
      })
      .catch(function (error) {
        setFailedLogin(true);
        console.log(error);
      });
  };

  return (
    <>
      <Box
        sx={{
          display: "flex",
          flexWrap: "wrap",
          "& > :not(style)": {
            m: 1,
            width: 650,
            height: 620,
          },
        }}
      >
        <Paper>
          <h1>Welcome to SofaSoGood</h1>
          <img src={sofaLogo} className="logo" alt="Sofasogood logo" />
          {/* <h3>Login</h3> */}
          <div>
            <div style={{ padding: "3px" }}>
              <TextField
                id="outlined-basic"
                label="Username"
                variant="outlined"
                onChange={(event) => {
                  setUserName(event.target.value);
                }}
              />
            </div>
            <div style={{ padding: "3px" }}>
              <TextField
                id="outlined-basic"
                type="password"
                label="Password"
                variant="outlined"
                onChange={(event) => {
                  setPassword(event.target.value);
                }}
              />
            </div>
            <div
              sx={{ display: "flex", flexDirection: "column", gap: "20px" }}
              style={{ padding: "10px" }}
            >
              <Button
                variant="contained"
                onClick={() => handleLogin()}
                sx={{
                  borderRadius: 2,
                  px: 3,
                  py: 1,
                  fontWeight: "bold",
                  textTransform: "none",
                }}
              >
                Login
              </Button>
              <Button
                component={Link}
                variant="contained"
                href="/CreateAccount"
                sx={{
                  borderRadius: 2,
                  px: 3,
                  py: 1,
                  fontWeight: "bold",
                  textTransform: "none",
                }}
              >
                Create Account
              </Button>
            </div>
          </div>
          <div>
            {failedLogin && (
              <Button
                variant="contained"
                onClick={() => setFailedLogin(false)}
                sx={{
                  borderRadius: 2,
                  px: 3,
                  py: 1,
                  fontWeight: "bold",
                  textTransform: "none",
                }}
              >
                Retry?
              </Button>
            )}
          </div>
        </Paper>
      </Box>
    </>
  );
};

export { Login };
