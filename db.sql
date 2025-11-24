CREATE DATABASE IF NOT EXISTS job_order_system;
USE job_order_system;
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  role VARCHAR(30) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS job_orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(100),
  item_description TEXT,
  quantity INT,
  deadline DATE,
  status VARCHAR(50) DEFAULT 'Pending Layout',
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (created_by) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jo_id INT,
  status VARCHAR(50),
  updated_by INT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jo_id) REFERENCES job_orders(id),
  FOREIGN KEY (updated_by) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  job_order_id INT DEFAULT NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_order_id) REFERENCES job_orders(id) ON DELETE SET NULL
);
-- After import, run: php tools/create_admin.php admin yourpassword to get SQL to insert admin
