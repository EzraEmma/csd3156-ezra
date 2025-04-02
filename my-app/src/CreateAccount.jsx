/*!************************************************************************
 * \file CreateAccount.jsx
* \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for CreateAccount Page.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/

import React, {useState, useEffect} from 'react';
import {Box,
    TextField,
    Button,
    styled,
    Paper} from '@mui/material';
import sofaLogo from './assets/sofasogoodicon.png'
import {Link, useLocation} from 'wouter'
import CloudUploadIcon from '@mui/icons-material/CloudUpload';
import axios from "axios";
import { PHP_URL } from './AppInclude';

const VisuallyHiddenInput = styled('input')({
  clip: 'rect(0 0 0 0)',
  clipPath: 'inset(50%)',
  height: 1,
  overflow: 'hidden',
  position: 'absolute',
  bottom: 0,
  left: 0,
  whiteSpace: 'nowrap',
  width: 1,
});

const CreateAccount = () => {
  // const [firstName, setFirstName] = React.useState('');
  // const [lastName, setLastName] = React.useState('');
  const [userName, setUserName] = useState('');
  const [password1, setPassword1] = useState('');
  const [password2, setPassword2] = useState('');
  const [img, setImg] = useState('');
  const [pwMatch, setPwMatch] = useState(false);
  const [, setLocation] = useLocation();

  const handleSubmit = () => {
    axios.post(`${PHP_URL}/PostNewAccount.php`, {
      Username: userName,
      pw: password1,
      ImageURL: img,
    },{
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    })
    .then(function (response) {
      console.log(response);
    })
    .catch(function (error) {
      console.log(error);
    });


    setLocation('/');
  }

  useEffect(() => {
      if (password1 == password2 &&
        password1 != '' &&
        password2 != '' &&
        userName != ''
      ) {
        setPwMatch(true)
      }
      else
      {
        setPwMatch(false);
      }
    }, [password1, password2,userName]);

    return <>
    <Box
      sx={{
        display: 'flex',
        flexWrap: 'wrap',
        padding: '20px',
        '& > :not(style)': {
          m: 1,
          width: 650,
          height: 620,
        },
      }}
    >
        <Paper sx={{ padding: '20px', display: 'flex', flexDirection: 'column', gap: '20px' }}>
          {/* Flex container for the image and heading */}
          <div style={{ display: 'flex', alignItems: 'center', gap: '20px' }}>
            <img src={sofaLogo} className="logo" alt="Sofasogood logo" style={{ width: '100px', height: 'auto' }} />
            <h1>Become a member at SofaSoGood</h1>
          </div>

          {/* <div style={{ display: 'flex', gap: '20px' }}>
            <TextField
              required
              id="outlined-basic"
              label="First Name"
              variant="outlined"
              value={firstName}
              onChange={(event) => {
                setFirstName(event.target.value);
              }}
            />
            <TextField
              required
              id="outlined-basic"
              label="Last Name"
              variant="outlined"
              onChange={(event) => {
                setLastName(event.target.value);
              }}
            />
          </div> */}

          <TextField
            required
            id="outlined-basic"
            label="Username"
            variant="outlined"
            value={userName}
            onChange={(event) => {
              setUserName(event.target.value);
            }}
          />
          <TextField
            required
            id="outlined-basic"
            type='password'
            label="Password"
            variant="outlined"
            value={password1}
            onChange={(event) => {
              setPassword1(event.target.value);
            }}
          />
          <TextField
            required
            id="outlined-basic"
            type='password'
            label="Confirm Password"
            variant="outlined"
            value={password2}
            onChange={(event) => {
              setPassword2(event.target.value);
            }}
          />
          <TextField
            required
            id="outlined-basic"
            label="Image"
            variant="outlined"
            onChange={(event) => {
              setImg(event.target.value);
            }}
          />
          {img != '' && <img src={img} alt="account logo" />}
          <div>
            {pwMatch &&
            <Button
              variant="contained"
              onClick={() => handleSubmit()}
              sx={{
                borderRadius: 2,
                px: 3,
                py: 1,
                fontWeight: 'bold',
                textTransform: 'none',
              }}
            >
              Create Account
            </Button>}
          </div>
        </Paper>
    </Box>
    </>
}

export {CreateAccount};