//
//  modelMyTrip.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "modelHttpBase.h"
#import "ASIHTTPRequest.h"

@interface modelMyTrip : modelHttpBase

@property NSString *__APIDOMAIN;

- (BOOL) fetchMyTripList: (NSInteger *)uid page:(NSInteger)page;
- (BOOL) fetchTripPhotos: (NSInteger *)uid withTripId:(NSInteger *)tripId page:(NSInteger)page;
- (BOOL) addTripNamed:(NSString*)title who:(NSInteger)uid startAt: (NSString *)startDate forDays:(NSInteger)days toDestination:(NSString *) dest;
- (BOOL) editTripByid:(int)id named:(NSString*)title who:(NSInteger)uid startAt: (NSString *)startDate forDays:(NSInteger)days toDestination:(NSString *) dest;
- (BOOL) cancelTripByid:(int)id who:(NSInteger)uid;
@end
