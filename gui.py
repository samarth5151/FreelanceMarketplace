import tkinter as tk
from tkinter import messagebox
import mysql.connector

# Connect to MySQL Database
def connect_db():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",      # Your MySQL username
            password="password",  # Your MySQL password
            database="testdb"
        )
        return conn
    except mysql.connector.Error as e:
        messagebox.showerror("Error", f"Error connecting to database: {e}")
        return None

# Insert Data into Database
def insert_data():
    name = name_entry.get()
    price = price_entry.get()

    if name and price:
        conn = connect_db()
        if conn:
            cursor = conn.cursor()
            query = "INSERT INTO items (name, price) VALUES (%s, %s)"
            cursor.execute(query, (name, price))
            conn.commit()
            conn.close()
            messagebox.showinfo("Success", "Item inserted successfully!")
            display_data()
    else:
        messagebox.showerror("Error", "Name and Price are required")

# Display Data from Database
def display_data():
    conn = connect_db()
    if conn:
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM items")
        rows = cursor.fetchall()
        result_box.delete(1.0, tk.END)
        for row in rows:
            result_box.insert(tk.END, f"ID: {row[0]}, Name: {row[1]}, Price: {row[2]}\n")
        conn.close()

# Update Data in Database
def update_data():
    item_id = id_entry.get()
    name = name_entry.get()
    price = price_entry.get()

    if item_id and name and price:
        conn = connect_db()
        if conn:
            cursor = conn.cursor()
            query = "UPDATE items SET name=%s, price=%s WHERE id=%s"
            cursor.execute(query, (name, price, item_id))            conn.commit()
            conn.close()
            messagebox.showinfo("Success", "Item updated successfully!")
            display_data()
    else:
        messagebox.showerror("Error", "ID, Name, and Price are required")

# Delete Data from Database
def delete_data():
    item_id = id_entry.get()

    if item_id:
        conn = connect_db()
        if conn:
            cursor = conn.cursor()
            query = "DELETE FROM items WHERE id=%s"
            cursor.execute(query, (item_id,))
            conn.commit()
            conn.close()
            messagebox.showinfo("Success", "Item deleted successfully!")
            display_data()
    else:
        messagebox.showerror("Error", "ID is required")

# Create the Tkinter Window
root = tk.Tk()
root.title("Simple MySQL GUI")
root.geometry("400x400")

# Create Input Fields and Labels
tk.Label(root, text="ID (for update/delete):").pack(pady=5)
id_entry = tk.Entry(root)
id_entry.pack()

tk.Label(root, text="Name:").pack(pady=5)
name_entry = tk.Entry(root)
name_entry.pack()

tk.Label(root, text="Price:").pack(pady=5)
price_entry = tk.Entry(root)
price_entry.pack()

# Create Buttons for CRUD Operations
insert_button = tk.Button(root, text="Insert", command=insert_data)
insert_button.pack(pady=5)

update_button = tk.Button(root, text="Update", command=update_data)
update_button.pack(pady=5)

delete_button = tk.Button(root, text="Delete", command=delete_data)
delete_button.pack(pady=5)

display_button = tk.Button(root, text="Display All", command=display_data)
display_button.pack(pady=5)

# Text Box to Display the Results
result_box = tk.Text(root, height=10, width=50)
result_box.pack(pady=10)

# Run the Tkinter Event Loop
root.mainloop()
