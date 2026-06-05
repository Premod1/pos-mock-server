# POS Card Machine Connection & User Guide

Welcome to the POS Mock Server. This guide outlines the setup and workflow for connecting and operating the POS Card Machine Terminal.

---

## 🚀 Step-by-Step Guide

### 1. Terminal Configuration
* Locate the **Wi-Fi Terminal Settings** card on the dashboard.
* Enter the **Card Machine IP Address** and **Port**.
* Click **Update Settings** to save the configuration and establish a connection.

### 2. Initiating a Sale
* Navigate to the **Simulate New Sale** card.
* Enter the required **Sale Amount** (in LKR).
* Enter the **Customer Mobile Number** (which is optional).
* Click **Generate Sale** to push the transaction into the queue.

### 3. Card Authorization
* Once a sale is submitted, it is added to the queue as `PENDING_CARD_PAYMENT`.
* A payment request is automatically transmitted to the configured POS card machine.
* The card terminal will prompt the customer to **tap, swipe, or insert (dip)** the card.

### 4. Status Update
* Once the transaction is authorized, the status on the dashboard will instantly update to **PAID**.
* If the connection fails or the card transaction is declined, the status will remain pending or show the failure details in the raw payload.

---

## 📸 Dashboard Preview

Below is the user interface of the POS Mock Server dashboard:

![POS Card Machine Dashboard Interface](./Screenshot%20from%202026-06-05%2022-26-19.png)
