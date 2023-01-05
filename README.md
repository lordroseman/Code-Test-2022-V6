# Code Test 2022 V6
 
My first thought about the code is, it's too long, there are so many things going on with the code.
I think it will be much better if we slice up the code and create specific class or services for its functions.
Also I am quite confused, the class is called BookingController, and BookingReposistory but the model you are
working with is called Job. I think it is much better if we rename it to JobBookingController and JobBookingRepository
so that It will be much clearer and easy to read.

Some of the codes are redundant especially on the part where you are validating the request, so I created a sample Request file
called JobRequest, here you can validate the request before it proceeds to your code. Some of the code is okay, but the overwhelming 
part is that it is too long. I don't think it adheres to SOLID principles, If I will be given a longer time, I will be happy refactor
the whole code.

I saw that you are using $request->all(), this is not a good practice, get only on the request what the methods need, and much better with validation if needed.

Also since this is an API, It is much better to return a response with proper HTTP Code. All of the response is responding a 200 HTTP codes by default, and in the Front-End side if theres a error, it will not be catch in error block of the request(ex. axios.onError)

