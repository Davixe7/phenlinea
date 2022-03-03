require('./bootstrap');

import { initializeApp } from 'firebase/app'
import { getToken, onMessage, getMessaging } from "firebase/messaging";

const firebaseConfig = {
  apiKey: "AIzaSyALW37c_FgXEAJ69_FYgTn9P4pwseOhXlQ",
  authDomain: "push-phenlinea.firebaseapp.com",
  projectId: "push-phenlinea",
  storageBucket: "push-phenlinea.appspot.com",
  messagingSenderId: "36897832935",
  appId: "1:36897832935:web:99dfe5a5063dd6eef5c742"
}

const app = initializeApp(firebaseConfig)
const messaging = getMessaging(app)

onMessage(messaging, payload => {
  navigator.serviceWorker.getRegistration('/firebase-cloud-messaging-push-scope')
  .then(registration => {
    console.log( payload )
    registration.showNotification(
      payload.data.title,
    )
  })
}) 

getToken(messaging, { vapidKey: "BF7Bz_JRDYkETztHZnvtw3ctg_u7tsg-Cvhko19bA7fi93B-75WqQZlMFSF8Eo16wXewl--1llh6rLXUYDpfWds" })
.then(currentToken => console.log('The token is', currentToken), e => console.log(e))