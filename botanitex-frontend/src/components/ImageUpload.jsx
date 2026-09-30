import { useState } from 'react';
import axios from 'axios';

export default function ImageUpload() {
  const [file, setFile] = useState(null);

  const handleUpload = async (e) => {
    e.preventDefault();
    if (!file) {
      alert("Please select a file first!");
      return;
    }

    const formData = new FormData();
    formData.append('image', file);

    try {
      // Update this URL to match your local backend endpoint
      const response = await axios.post('http://localhost:5000/api/upload', formData);
      console.log("Server Response:", response.data);
      alert("Upload successful! Check console for details.");
    } catch (error) {
      console.error("Upload Error:", error);
      alert("Upload failed. Make sure your backend server is running.");
    }
  };

  return (
    <div>
      <form onSubmit={handleUpload}>
        <input
          type="file"
          accept="image/*"
          onChange={(e) => setFile(e.target.files[0])}
        />
        <button type="submit">Upload Image</button>
      </form>
    </div>
  );
}