# Use a Node.js base image
FROM node:16

# Set the working directory inside the container
WORKDIR /app

# Copy package.json and yarn.lock first to leverage Docker cache
COPY package.json yarn.lock ./

# Install dependencies
RUN yarn install

# Copy the rest of the application
COPY . .

# Build the SvelteKit app
RUN yarn run build

# Expose the port the app will run on
EXPOSE 5000

# Start the application
CMD ["yarn", "dev"]
