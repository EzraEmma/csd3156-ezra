/*!************************************************************************
 * \file Catalogue.jsx
 * \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for Catalogue Page.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/
import React, { useState, useEffect } from "react";
import { Box, ImageList, ImageListItem, ImageListItemBar } from "@mui/material";
import "./style/Catalogue.css";
import AppBarComponent from "./AppBarComponent.jsx";
import { Link, useLocation } from "wouter";
import { PHP_URL } from "./AppInclude.jsx";
import axios from "axios";

const Catalogue = ({ id }) => {
  const [, setLocation] = useLocation();
  const [product, setProduct] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchData = async () => {
    try {
      setLoading(true);
      const { data } = await axios.get(`${PHP_URL}/GetProductList.php`);
      setProduct(data);
    } catch (err) {
      setError(err.message || "Failed to fetch product");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  // console.log(product);

  return (
    <>
      <AppBarComponent id={id}/>
      <ImageList cols={5} gap={8}>
        {product.map((item) => (
          <ImageListItem
            key={item.InventoryID}
            onClick={() => setLocation(`/ViewProduct/${id}#${item.InventoryID}`)}
            style={{ cursor: "pointer" }}
          >
            <img src={`${item.Image}`} alt={item.Name} loading="lazy" />
            <ImageListItemBar
              title={item.Name}
              subtitle={
                <div className="subtitile_div">
                  <span>by: {item.Seller}</span>
                  <span className="item_price">{`\$${item.Price}`}</span>
                </div>
              }
              position="below"
            />
          </ImageListItem>
        ))}
      </ImageList>
    </>
  );
};

export { Catalogue };
