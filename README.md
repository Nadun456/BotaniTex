# BotaniTex: Botanical Vein-Based Textile Pattern Generation

BotaniTex is an AI-powered web-based system designed to automatically generate unique, seamless, and print-ready textile patterns from botanical images. The system uses computer vision to extract structural elements from flowers and leaves—such as veins, colors, textures, and shapes—and utilizes generative AI to synthesize novel textile patterns optimized for cotton digital printing. 

To protect intellectual property and ensure the integrity of the generated designs, BotaniTex incorporates a blockchain-based provenance mechanism that records cryptographic and perceptual fingerprints of the patterns.

## Key Features
* **Botanical Feature Extraction:** Utilizes computer vision (OpenCV) to extract leaf shape, vein structure, color, and texture from uploaded botanical images.
* **Generative AI Pattern Creation:** Employs generative AI and deep learning models to create original, seamless repeating patterns rather than simply modifying existing images.
* **Customization:** Allows users to modify generated patterns by adjusting colors, styles, and pattern density.
* **Print-Ready Export:** Generates high-resolution textile designs that are ready for immediate use in digital textile printing workflows.
* **Blockchain Provenance & Validation:** Registers pattern metadata and SHA-256/perceptual fingerprints on an Ethereum-compatible blockchain using Solidity smart contracts to verify the origin and parent-child relationships of generated designs.

## Technology Stack
* **Frontend:** Vue.js / React.js
* **Backend:** Laravel (PHP) / Node.js (Express.js)
* **Database:** MySQL
* **AI & Image Processing:** Python, OpenCV, Generative AI / Deep Learning Models
* **Blockchain:** Ethereum-compatible test/local blockchain, Solidity, SHA-256, pHash, C2PA

## Team & Supervision
This project was prepared for the EER6689 Final Project in Software Engineering at The Open University of Sri Lanka.
* **Supervised by:** Dr. K.V.J.P. Ekanayake
* **Developed by Group 10:** 
  * DGM Saubagya
  * KWPGAN Samarathunga
  * ULS Riffna Banu 
  * AH Zeenath Hana
  * A Afrina Ilahi
