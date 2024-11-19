USE MadTestLab;

CREATE TABLESPACE TDE
ADD DATAFILE 'TDE.ibd'
ENCRYPTION='Y';

ALTER INSTANCE ROTATE INNODB MASTER KEY;

CREATE TABLE Accounts (
    AccountID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARBINARY(256) NOT NULL,
    Role VARCHAR(20) NOT NULL,
    AccountStatus VARCHAR(20) NOT NULL,
    Credentials VARCHAR(100) NOT NULL,
    IV VARBINARY(16) NOT NULL
) ENCRYPTION='Y';

CREATE TABLE Patients (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARCHAR(8) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID)
) ENCRYPTION='Y';

CREATE TABLE Secretaries (
    SecretaryID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARCHAR(8) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID)
) ENCRYPTION='Y';

CREATE TABLE LabStaffs (
    LabStaffID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARCHAR(8) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID)
) ENCRYPTION='Y';

CREATE TABLE Insurances (
    InsuranceID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    InsuranceName VARCHAR(100) NOT NULL,
    InsuranceAmount DECIMAL(10, 2) NOT NULL,
    InsuranceDetails TEXT,
    InsuranceStatus VARCHAR(20) NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID)
) ENCRYPTION='Y';


CREATE TABLE TestsCatalog (
    TestCode INT AUTO_INCREMENT PRIMARY KEY,
    TestName VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    TestType VARCHAR(20) NOT NULL 
) ENCRYPTION='Y';

CREATE TABLE Appointments (
    AppointmentID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    SecretaryID INT NOT NULL,
    AppointmentDateTime DATETIME NOT NULL,
    AppointmentsStatus VARCHAR(20) NOT NULL, 
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (SecretaryID) REFERENCES Secretaries(SecretaryID)
) ENCRYPTION='Y';


CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    LabStaffID INT NOT NULL,
    SecretaryID INT NOT NULL,
    TestCode INT NOT NULL,
    OrderDateTime DATETIME NOT NULL,
    OrderStatus ENUM('Pending', 'Completed', 'Cancelled') NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (LabStaffID) REFERENCES LabStaffs(LabStaffID),
    FOREIGN KEY (SecretaryID) REFERENCES Secretaries(SecretaryID),
    FOREIGN KEY (TestCode) REFERENCES TestsCatalog(TestCode)
) ENCRYPTION='Y';


CREATE TABLE Bills (
    BillID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    InsuranceID INT NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL,
    PaymentStatus ENUM('Paid', 'Unpaid', 'Pending') NOT NULL,
    BillDateTime DATETIME NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (InsuranceID) REFERENCES Insurances(InsuranceID)
) ENCRYPTION='Y';

CREATE TABLE Results (
    ResultID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    LabStaffID INT NOT NULL,
    ReportURL VARCHAR(255) NOT NULL,
    Interpretation TEXT NOT NULL,
    ResultDateTime DATETIME NOT NULL,
    ResultStatus VARCHAR(20) NOT NULL,  
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (LabStaffID) REFERENCES LabStaffs(LabStaffID)
) ENCRYPTION='Y';
