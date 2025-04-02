import { useState /*, useEffect*/ } from "react";
import { Catalogue } from "./Catalogue.jsx";
import { Login } from "./Login.jsx";
import { CreateAccount } from "./CreateAccount.jsx";
import { Profile } from "./Profile.jsx";
import { CreateListings } from "./CreateListings.jsx";
import { ViewProduct } from "./ViewProduct.jsx";
import { Cart } from "./Cart.jsx";
import { Route, Router } from "wouter";
//import { API_URL } from "./AppInclude.jsx";

function App() {
  const [count, setCount] = useState(0);

  // const [timestamp, setTimestamp] = useState("Loading...");

  // useEffect(() => {
  //   fetch(`${API_URL}/HelloWorldTimestamp.php`)
  //     .then((response) => response.json())
  //     .then((data) => setTimestamp(data.timestamp))
  //     //.then((response) => console.log(response))
  //     .catch((error) => console.error("Error fetching timestamp:", error));
  // }, []);

  return (
    <>
      {/* <div>
        <h1>Current Timestamp</h1>
        <p>{timestamp}</p>
      </div> */}
      <Router>
        {/* <HomeButton />  */}
        <Route path="/" component={Login} />
        <Route path="/Catalogue" component={Catalogue} />
        <Route path="/CreateAccount" component={CreateAccount} />
        <Route path="/Profile" component={Profile} />
        <Route path="/CreateListings" component={CreateListings} />
        <Route path="/ViewProduct" component={ViewProduct} />
        <Route path="/Cart" component={Cart} />
      </Router>
    </>
  );
}

export default App;
