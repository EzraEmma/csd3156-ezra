/*!************************************************************************
 * \file App.jsx
 * \author	 Kenzie Lim  | kenzie.l\@digipen.edu
 * \par Course: CSD3156
 * \date 25/03/2025
 * \brief
 * This file defines the frontend for App for the main routing.
 *
 * Copyright 2025 DigiPen Institute of Technology Singapore All Rights Reserved
 **************************************************************************/
import { useState, useEffect } from "react";
import { Catalogue } from "./Catalogue.jsx";
import { Login } from "./Login.jsx";
import { CreateAccount } from "./CreateAccount.jsx";
import { Profile } from "./Profile.jsx";
import { CreateListings } from "./CreateListings.jsx";
import { ViewProduct } from "./ViewProduct.jsx";
import { Cart } from "./Cart.jsx";
import { Route, Router } from "wouter";
//import { PHP_URL } from "./AppInclude.jsx";

function App() {
  const [count, setCount] = useState(0);

  const [timestamp, setTimestamp] = useState("Loading...");

  // useEffect(() => {
  //   fetch(`${PHP_URL}/HelloWorldTimestamp.php`)
  //     .then((response) => response.json())
  //     .then((data) => setTimestamp(data.timestamp))
  //     //.then((response) => console.log(response))
  //     .catch((error) => console.error("Error fetching timestamp:", error));
  // }, []);

  return (
    <>
      <Router>
        <Route path="/">
          {() => <Login />}
        </Route>
        <Route path="/Catalogue/:id">
          {(params) => <Catalogue id={params.id} />}
        </Route>
        <Route path="/CreateAccount">
          {() => <CreateAccount />}
        </Route>
        <Route path="/Profile/:userID/:displayID">
          {(params) => <Profile userID={params.userID} displayID={params.displayID} />}
        </Route>
        <Route path="/CreateListings/:id">
          {(params) => <CreateListings id={params.id} />}
        </Route>
        <Route path="/ViewProduct/:id">
          {(params) => <ViewProduct id={params.id} />}
        </Route>
        <Route path="/Cart/:id">
          {(params) => <Cart id={params.id} />}
        </Route>
      </Router>
    </>
  );
}

export default App;
