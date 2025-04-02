/*!************************************************************************
 * \file ViewProduct.jsx
 * \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for ViewProduct Page.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/

import React, { useState, useEffect } from "react";
import {
  Box,
  TextField,
  Button,
  Avatar,
  Collapse,
  Typography,
  IconButton,
  Fade,
  styled,
  Paper,
} from "@mui/material";
import {
  Add as AddIcon,
  Remove as RemoveIcon,
  ShoppingCart as ShoppingCartIcon,
} from "@mui/icons-material";
import { Link, useLocation } from "wouter";
import AppBarComponent from "./AppBarComponent.jsx";
import ExpandMoreIcon from "@mui/icons-material/ExpandMore";
import ExpandLessIcon from "@mui/icons-material/ExpandLess";
import { PHP_URL } from "./AppInclude.jsx";
import axios from "axios";

const AddToCartButton = ({
  initialQuantity = 0,
  maxQuantity = 10,
  tmp,
  customerID,
}) => {
  const [quantity, setQuantity] = React.useState(initialQuantity);
  const [isExpanded, setIsExpanded] = React.useState(initialQuantity > 0);
  const [, setLocation] = useLocation();
  const id = sessionStorage.getItem("persistedId");

  useEffect(() => {
    if (quantity === 0) {
      setIsExpanded(false);
    }
  }, [quantity]);

  const handleAddToCart = () => {
    setQuantity(1);
    setIsExpanded(true);
  };

  const handleQuantityChange = (e) => {
    const value = Math.max(0, Math.min(maxQuantity, Number(e.target.value)));
    setQuantity(value);
  };

  const incrementQuantity = () => {
    setQuantity((prev) => Math.min(maxQuantity, prev + 1));
  };

  const decrementQuantity = () => {
    setQuantity((prev) => Math.max(0, prev - 1));
  };

  const handleConfirm = () => {
    if (quantity === 0) {
      setIsExpanded(false);
    }
    // console.log(`product ${JSON.striFngify(product[0])}`)
    // console.log(`product ${parseInt(product[0].Stock) - quantity}`)
    axios
      .post(
        `${PHP_URL}/PushAddCart.php`,
        {
          CustomerID: id,
          SellerID: tmp.SellerID,
          InventoryID: tmp.ID,
          Quant: quantity,
        },
        {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
        }
      )
      .then(function (response) {
        console.log(response);
      })
      .catch(function (error) {
        console.log(error);
      });

    setLocation(`/Catalogue/${id}`);
  };

  return (
    <Box
      sx={{ display: "flex", alignItems: "center", justifyContent: "center" }}
    >
      {!isExpanded ? (
        <Button
          variant="contained"
          color="primary"
          startIcon={<ShoppingCartIcon />}
          onClick={handleAddToCart}
          sx={{
            borderRadius: 2,
            px: 3,
            py: 1,
            fontWeight: "bold",
            textTransform: "none",
          }}
        >
          Add to Cart
        </Button>
      ) : (
        <Fade in={isExpanded}>
          <Paper elevation={2}>
            <IconButton
              onClick={decrementQuantity}
              aria-label="Decrease quantity"
            >
              <RemoveIcon fontSize="small" />
            </IconButton>

            <TextField
              value={quantity}
              onChange={handleQuantityChange}
              type="number"
              inputProps={{
                min: 0,
                max: maxQuantity,
                style: {
                  textAlign: "center",
                  width: "40px",
                  padding: "8px 0",
                },
              }}
              variant="standard"
              sx={{
                mx: 1,
                "& .MuiInput-underline:before": {
                  borderBottom: "none",
                },
                "& .MuiInput-underline:after": {
                  borderBottom: "none",
                },
              }}
            />

            <IconButton
              onClick={incrementQuantity}
              disabled={quantity >= maxQuantity}
              aria-label="Increase quantity"
            >
              <AddIcon fontSize="small" />
            </IconButton>

            <Button
              variant="contained"
              color="primary"
              onClick={handleConfirm}
              sx={{
                ml: 1,
                borderRadius: 2,
                px: 2,
                py: 0.5,
                fontWeight: "bold",
                textTransform: "none",
              }}
            >
              {quantity === 0 ? "Remove" : "Update"}
            </Button>
          </Paper>
        </Fade>
      )}
    </Box>
  );
};

const ViewProduct = ({id}) => {
  const productID = window.location.hash.split("#")[1];
  const [open, setOpen] = React.useState(true);
  const [product, setProduct] = useState(null);
  const [profile, setProfile] = useState(null);
  const [profileID, setProfileID] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchData = async () => {
    try {
      setLoading(true);
      const { data } = await axios.get(`${PHP_URL}/GetProductInfo.php`, {
        params: {
          ID: productID,
        },
      });
      setProduct(data);
    } catch (err) {
      setError(err.message || "Failed to fetch product");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (productID) {
      fetchData();
    }
  }, [productID]);

  const handleClick = () => {
    setOpen(!open);
  };

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;
  if (!product) return <div>No product found</div>;

  // console.log(product);
  // console.log(profile);

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
          {/* Flex container for the image and heading */}
          <div style={{ display: "flex", alignItems: "center", gap: "20px" }}>
            <img
              src={product[0].Image}
              alt="product "
              style={{ width: "400px", height: "auto" }}
            />
            <div>
              <h1
                style={{ margin: 0, display: "flex", fontSize: "2.9em" }}
              >{`${product[0].Name}`}</h1>
              <Link href={`/Profile/${id}/${product[0].SellerID}`}>
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                    gap: "5px",
                    cursor: "pointer",
                  }}
                >
                  <Avatar
                    alt={`${product[0].SellerUserName}`}
                    src={`${product[0].SellerProfilePicture}`}
                    sx={{ width: 24, height: 24 }}
                  />
                  <p
                    style={{
                      display: "flex",
                      justifyContent: "start",
                      color: "grey",
                      margin: 0,
                    }}
                  >
                    {`${product[0].SellerUserName}`}
                  </p>
                </div>
              </Link>
              <p
                style={{
                  display: "flex",
                  fontSize: "1.7em",
                  fontWeight: "bold",
                }}
              >{`\$${product[0].Price}`}</p>
            </div>
          </div>
          <div
            style={{
              display: "flex",
              alignItems: "center",
              justifyContent: "space-around",
            }}
          >
            <p>Description:</p>
            <IconButton
              size="large"
              color="inherit"
              onClick={() => handleClick()}
            >
              {open ? <ExpandLessIcon /> : <ExpandMoreIcon />}
            </IconButton>
          </div>
          <Collapse in={open} timeout="auto" unmountOnExit>
            <p style={{ margin: 0, display: "flex" }}>
              {product[0].Description}
            </p>
          </Collapse>

          <AddToCartButton
            style={{ width: "200px", margin: "0 auto" }}
            tmp={product[0]}
            customerID={product[0].SellerID}
          />
        </Paper>
      </Box>
    </>
  );
};

export { ViewProduct };
