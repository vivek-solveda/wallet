Functional Document for Customer Wallet Module

Module Name: Solveda_Wallet
Magento Version: 2.4.5
PHP Version: 8.1

Objective

1.The Customer Wallet Module allows Magento store customers to:

2.Add money to their wallet.

3.View transaction history.

Maintain and display their wallet balance.

This document outlines the module’s functionality, database structure, and implementation details.

Features

1. Add Money to Wallet

Customers can add money to their wallet via a form on their account dashboard.

The added amount is updated in the wallet_balance table.

A corresponding transaction is recorded in the wallet_transaction table.

2. Transaction History

Customers can view a list of all wallet transactions, including:

Amount

Transaction Type (Credit/Debit)

Date of Transaction

Transactions are displayed in descending order of date.

3. Wallet Balance Management

The module maintains a separate table wallet_balance for each customer’s wallet balance.

Balance updates are synchronized with transact
