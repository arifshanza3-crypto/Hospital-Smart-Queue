<link href="{{ asset('css/contect form.css') }}" rel="stylesheet">

<section class="contact-section">
    <div class="section-container">
        <div class="content-side">
            <span class="label-accent">Get In Touch</span>
            <h2 class="main-title">
                Have Questions? <br>
                <span class="title-accent">We're Here to Help</span>
            </h2>
            <p class="sub-text">
                Whether you are a hospital looking to join our network or a patient needing support, our team is always ready to assist you.
            </p>
            
            <div class="contact-info-list">
                <h4 class="info-heading">System Operations</h4>
                <ul class="details">
                    <li><i class="fas fa-map-marker-alt"></i> Smart Queue - Pakistan</li>
                    <li><i class="fas fa-clock"></i> Token Generation: Available 24/7</li>
                    <li><i class="fas fa-headset"></i> Support: Mon - Sat (9:00 AM - 6:00 PM)</li>
                </ul>
            </div>
        </div>

        <div class="form-side">
            <form id="contactForm" class="teal-form">
                @csrf
                <h3 class="form-title">Send a Message</h3>
                
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="example@hospital.com" required>
                </div>

                <div class="form-group">
                    <label for="message">How can we help?</label>
                    <textarea id="message" name="message" rows="3" placeholder="Tell us about your clinic or your issue..."></textarea>
                </div>

                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>
    </div>
</section>

<script src="{{ asset('js/contect form.js') }}"></script>