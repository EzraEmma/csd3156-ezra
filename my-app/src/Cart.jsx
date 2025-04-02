/*!************************************************************************
 * \file Cart.jsx
 * \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for Cart Page.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/
import React, { useState, useEffect } from "react";
import {
  Box,
  TextField,
  Button,
  Card,
  CardMedia,
  CardContent,
  CardActionArea,
  Typography,
  IconButton,
  Paper,
} from "@mui/material";
import { useLocation } from "wouter";
import AppBarComponent from "./AppBarComponent.jsx";
import { PHP_URL } from "./AppInclude.jsx";
import axios from "axios";

const Cart = ({ id }) => {
  const [total, setTotal] = useState(0);
  const [, setLocation] = useLocation();
  //const { id } = useParams();
  const [cart, setCart] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchData = async () => {
    try {
      setLoading(true);
      const { data } = await axios.get(`${PHP_URL}/GetUserCart.php`, {
        params: {
          ID: id,
        },
      });
      initCart(data);
      console.log(data);
    } catch (err) {
      setError(err.message || "Failed to fetch product");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (id) {
      fetchData();
      // console.log('in profile');
    }
  }, []);

  const initCart = (data) => {
    setCart(data);
    let tmp = 0;
    data.map((item) => {
      tmp += item.InventoryPrice * item.Quantity;
    });

    setTotal(tmp);
  };

  console.log(cart);
  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;
  if (!cart) return <div>No cart found</div>;

  return (
    <>
      <AppBarComponent id={id}/>
      <Box
        sx={{
          display: "flex",
          flexWrap: "wrap",
          "& > :not(style)": {
            m: 1,
            width: 650,
            minHeight: 620,
            height: "100%",
          },
        }}
      >
        <Paper
          sx={{
            padding: "20px",
            display: "flex",
            flexDirection: "column",
            gap: "20px",
          }}
        >
          <h1 style={{ display: "flex", margin: 0 }}>Your Cart</h1>
          {cart.map((item) => (
            <Card key={item.InventoryID} sx={{ display: "flex" }}>
              <CardActionArea
                onClick={() => setLocation(`/ViewProduct/${id}#${item.InventoryID}`)}
              >
                <Box sx={{ display: "flex", flexDirection: "row" }}>
                  <CardMedia
                    component="img"
                    image={item.InventoryImage}
                    alt="product"
                    style={{ display: "flex", width: "100px", height: "100px" }}
                  />
                  <CardContent>
                    <Typography component="div" variant="h5">
                      {item.InventoryName}
                    </Typography>
                    <Typography
                      variant="subtitle1"
                      component="div"
                      sx={{ color: "text.secondary" }}
                    >
                      {`\$${item.InventoryPrice}`}
                    </Typography>
                  </CardContent>
                  <div
                    style={{
                      display: "flex",
                      alignItems: "center",
                      width: "100%",
                      justifyContent: "end",
                      marginRight: "20px",
                    }}
                  >
                    <Typography
                      variant="subtitle1"
                      component="div"
                      sx={{ color: "text.secondary" }}
                    >
                      Qty: {item.Quantity}
                    </Typography>
                  </div>
                </Box>
              </CardActionArea>
            </Card>
          ))}
          <p style={{ display: "flex" }}>Total: ${total}</p>
        </Paper>
      </Box>
    </>
  );
};

export { Cart };
