# Minimal Dockerfile for PressIQ Widgets - Coolify deployment test
# This simulates a WordPress plugin deployment environment

FROM php:8.2-cli

# Install Node.js for linting/testing
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy project files
COPY . .

# For Coolify, we validate the structure is deployable
# In a real WordPress deployment, this would be copied to wp-content/plugins/

# Validate PHP syntax
RUN find . -name "*.php" -exec php -l {} \; 2>&1 | grep -v "No syntax errors" || true

# Run linting
RUN npm ci && npm run lint:js && npm run lint:css

# Build step (placeholder for future builds)
RUN npm run build

# Validate deployment structure
RUN echo "PressIQ Widgets deployment validation complete"
