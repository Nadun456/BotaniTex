export default function ImageUpload() {
  const handleMockUpload = (e) => {
    e.preventDefault();
    alert("Button clicked! (No API connected yet)");
  };

  return (
    <div>
      <button onClick={handleMockUpload}>Upload Image (Mock)</button>
    </div>
  );
}