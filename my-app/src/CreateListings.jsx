/*!************************************************************************
 * \file CreateListings.jsx
 * \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for CreateListings Page.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/
import React, { useState, useEffect } from "react";
import {
  Box,
  TextField,
  Button,
  Modal,
  Avatar,
  Card,
  CardMedia,
  CardContent,
  Typography,
  IconButton,
  styled,
  Paper,
} from "@mui/material";
import DeleteIcon from "@mui/icons-material/Delete";
import EditIcon from "@mui/icons-material/Edit";
import { Link, useLocation } from "wouter";
import AppBarComponent from "./AppBarComponent.jsx";
import { PHP_URL } from "./AppInclude.jsx";
import axios from "axios";
import "./style/CreateListing.css";

//creates an overlay that allows u to add information inside

const style = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 400,
  bgcolor: "background.paper",
  border: "2px solid #000",
  boxShadow: 24,
  p: 4,
  padding: "20px",
};

const VisuallyHiddenInput = styled("input")({
  clip: "rect(0 0 0 0)",
  clipPath: "inset(50%)",
  height: 1,
  overflow: "hidden",
  position: "absolute",
  bottom: 0,
  left: 0,
  whiteSpace: "nowrap",
  width: 1,
});

const CreateListings = ({ id }) => {
  const [open, setOpen] = useState(false);
  const [isEditing, setEditing] = useState(false);
  //const { id } = useParams();
  const profileID = id;
  const [, setLocation] = useLocation();
  const [profile, setProfile] = useState([]);
  const [profileListings, setProfileListings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [productName, setProductName] = useState("");
  const [productPrice, setProductPrice] = useState("");
  const [productImg, setProductImg] = useState("");
  const [productQuantity, setProductQuantity] = useState("");
  const [productDesc, setProductDesc] = useState("");
  const [productID, setProductID] = useState("");
  const handleOpen = () => setOpen(true);
  const handleClose = () => setOpen(false);

  const handleEditOpen = () => setEditing(true);
  const handleEditClose = () => setEditing(false);

  const handleDelete = (id) => {
    axios
      .post(
        `${PHP_URL}/PostRemoveInventory.php`,
        {
          InventoryID: id,
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

    fetchData();
  };

  const handleNewListing = () => {
    axios
      .post(
        `${PHP_URL}/PostNewInventory.php`,
        {
          ID: id,
          Name: productName,
          Desc: productDesc,
          Quant: parseInt(productQuantity).toString(),
          Price: productPrice,
          ImagePath: productImg,
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

    handleClose();
  };

  const handleInitEdit = (tmp) => {
    setProductName(tmp.InventoryName);
    setProductPrice(tmp.Inventoryprice);
    setProductImg(tmp.InventoryImage);
    setProductQuantity(tmp.InventoryNumberInStock);
    setProductDesc(tmp.InventoryDescription);
    setProductID(tmp.InventoryID);

    handleEditOpen();
  };

  const handleSubmitEdit = () => {
    axios
      .post(
        `${PHP_URL}/PostEditInventory.php`,
        {
          ID: productID,
          Name: productName,
          Desc: productDesc,
          Price: productPrice,
          Quant: parseInt(productQuantity).toString(),
          ImagePath: productImg,
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

    handleEditClose();
    fetchData();
  };

  const fetchData = async () => {
    try {
      setLoading(true);
      const { data } = await axios.get(`${PHP_URL}/GetProfileInfo.php`, {
        params: {
          ID: profileID,
        },
      });
      setProfile(data);
    } catch (err) {
      setError(err.message || "Failed to fetch product");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (profileID) {
      fetchData();
      // console.log('in profile');
    }
  }, []);


  const fetchUserListings = async () => {
    try {
      setLoading(true);
      const { data } = await axios.get(`${PHP_URL}/GetUserListings.php`, {
        params: {
          ID: profileID,
        },
      });
      //console.log("Profile data:", JSON.stringify(data, null, 2));
      setProfileListings(data);
    } catch (err) {
      setError(err.message || "Failed to fetch product");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (profileID) {
      fetchUserListings();
      // console.log('in profile');
    }
  }, [profileID]);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;
  if (!profile) return <div>No profile found</div>;

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
          <h1 style={{ display: "flex", margin: 0 }}>Your Listings</h1>
          <div style={{ display: "flex", alignItems: "center", gap: "5px" }}>
            <Avatar
              alt={profile[0].Name}
              src={profile[0].PFP}
              sx={{ width: 24, height: 24 }}
            />
            <p
              style={{
                display: "flex",
                justifyContent: "start",
                color: "grey",
                margin: 0,
                width: "300px",
              }}
            >
              {`${profile[0].Name}`}
            </p>
            <div
              style={{
                display: "flex",
                alignItems: "center",
                gap: "20px",
                width: "100%",
                justifyContent: "end",
              }}
            >
              <Button
                variant="contained"
                onClick={() => handleOpen()}
                sx={{
                  borderRadius: 2,
                  px: 3,
                  py: 1,
                  fontWeight: "bold",
                  textTransform: "none",
                }}
              >
                Create New Listing
              </Button>
              <Modal open={open} onClose={() => handleClose()}>
                <Box sx={style}>
                  <p>Create a new Listing</p>
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Name"
                    className="modal_txtField"
                    variant="outlined"
                    value={productName}
                    onChange={(event) => {
                      setProductName(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Price"
                    className="modal_txtField"
                    variant="outlined"
                    value={productPrice}
                    onChange={(event) => {
                      setProductPrice(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Description"
                    className="modal_txtField"
                    variant="outlined"
                    value={productDesc}
                    onChange={(event) => {
                      setProductDesc(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Image"
                    className="modal_txtField"
                    variant="outlined"
                    value={productImg}
                    onChange={(event) => {
                      setProductImg(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Quantity"
                    className="modal_txtField"
                    variant="outlined"
                    value={productQuantity}
                    onChange={(event) => {
                      setProductQuantity(event.target.value);
                    }}
                  />
                  <div
                    style={{ display: "flex", padding: "0px 12px 12px 12px" }}
                  >
                    <Button
                      variant="contained"
                      className="modal_txtField"
                      onClick={() => handleNewListing()}
                      sx={{
                        borderRadius: 2,
                        px: 3,
                        py: 1,
                        fontWeight: "bold",
                        textTransform: "none",
                      }}
                    >
                      Add Listing
                    </Button>
                  </div>
                </Box>
              </Modal>

              <Modal open={isEditing} onClose={() => handleEditClose()}>
                <Box sx={style}>
                  <p>Edit Your Listing</p>
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Name"
                    className="modal_txtField"
                    variant="outlined"
                    value={productName}
                    onChange={(event) => {
                      setProductName(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Price"
                    className="modal_txtField"
                    variant="outlined"
                    value={productPrice}
                    onChange={(event) => {
                      setProductPrice(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Description"
                    className="modal_txtField"
                    variant="outlined"
                    value={productDesc}
                    onChange={(event) => {
                      setProductDesc(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Image"
                    className="modal_txtField"
                    variant="outlined"
                    value={productImg}
                    onChange={(event) => {
                      setProductImg(event.target.value);
                    }}
                  />
                  <TextField
                    required
                    id="outlined-basic"
                    label="Product Quantity"
                    className="modal_txtField"
                    variant="outlined"
                    value={productQuantity}
                    onChange={(event) => {
                      setProductQuantity(event.target.value);
                    }}
                  />
                  <div
                    style={{ display: "flex", padding: "0px 12px 12px 12px" }}
                  >
                    <Button
                      variant="contained"
                      className="modal_txtField"
                      onClick={() => handleSubmitEdit()}
                      sx={{
                        borderRadius: 2,
                        px: 3,
                        py: 1,
                        fontWeight: "bold",
                        textTransform: "none",
                      }}
                    >
                      Submit Edited Listing
                    </Button>
                  </div>
                </Box>
              </Modal>
            </div>
          </div>

          {profileListings.map((item) => (
            <Card key={item.InventoryID} sx={{ display: "flex" }}>
              <Box sx={{ display: "flex", flexDirection: "row" }}>
                <CardMedia
                  component="img"
                  image={item.InventoryImage}
                  alt="product "
                  style={{ display: "flex", width: "100px", height: "auto" }}
                />
                <CardContent style={{ width: "200px" }}>
                  <Typography component="div" variant="h5">
                    {item.InventoryName}
                  </Typography>
                  <Typography
                    variant="subtitle1"
                    component="div"
                    sx={{ color: "text.secondary" }}
                  >
                    {`Quantity: ${item.InventoryNumberInStock}`}
                  </Typography>
                </CardContent>
              </Box>
              <div
                style={{
                  display: "flex",
                  alignItems: "center",
                  width: "100%",
                  justifyContent: "end",
                }}
              >
                <IconButton
                  size="large"
                  color="inherit"
                  onClick={() => handleInitEdit(item)}
                >
                  <EditIcon />
                </IconButton>
                <IconButton
                  size="large"
                  color="inherit"
                  style={{ color: "red" }}
                  onClick={() => handleDelete(item.InventoryID)}
                >
                  <DeleteIcon />
                </IconButton>
              </div>
            </Card>
          ))}
        </Paper>
      </Box>
    </>
  );
};

export { CreateListings };
