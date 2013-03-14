//
//  modelMyTrip.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "modelMyTrip.h"



@implementation modelMyTrip
- (id)init
{
    [super init];
    self.__APIDOMAIN = @"http://trip.gulibaby.com/__api/mytrip";
    return self;
}

- (BOOL)fetchMyTripList:(NSInteger *)uid page:(NSInteger)page
{
    NSString *url = [NSString stringWithFormat:@"%@/api_mytrip_listtrip.php?uid=%d&page=%d" , self.__APIDOMAIN ,uid , page];
    
    [self saveLog:url];
    return [super doSynHttpGet:url];
}
- (BOOL)fetchTripPhotos:(NSInteger *)uid withTripId:(NSInteger *)tripId page:(NSInteger)page
{
    NSString *url = [NSString stringWithFormat:@"%@/api_mytrip_listtripphoto.php?uid=%d&trip_id=%d&page=%d" ,self.__APIDOMAIN, uid , tripId , page];
    [self saveLog:url];
    return [super doSynHttpGet:url];
}
- (BOOL)addTripNamed:(NSString *)title who:(NSInteger)uid startAt:(NSString *)startDate forDays:(NSInteger)days toDestination:(NSString *)dest
{
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];

    NSString *uidS = [NSString stringWithFormat:@"%d" , uid];
    NSString *daysS = [NSString stringWithFormat:@"%d" , days];
    
    [data setObject:title forKey:@"title"];
    [data setObject:uidS forKey:@"uid"];
    [data setObject:startDate forKey:@"startDate"];
    [data setObject:daysS forKey:@"days"];
    //[data setObject:dest forKey:@"destintion"];

    NSString *url = [NSString stringWithFormat:@"%@/api_mytrip_createtrip.php" , self.__APIDOMAIN];
    return [super doSynHttpPost:url postData:data];
}
- (BOOL)editTripByid:(int)id named:(NSString *)title who:(NSInteger)uid startAt:(NSString *)startDate forDays:(NSInteger)days toDestination:(NSString *)dest
{
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    
    NSString *uidS = [NSString stringWithFormat:@"%d" , uid];
    NSString *daysS = [NSString stringWithFormat:@"%d" , days];
    
    [data setObject:id forKey:@"id"];
    [data setObject:title forKey:@"title"];
    [data setObject:uidS forKey:@"uid"];
    [data setObject:startDate forKey:@"startDate"];
    [data setObject:daysS forKey:@"days"];
    //[data setObject:dest forKey:@"destintion"];
    
    NSString *url = [NSString stringWithFormat:@"%@/api_mytrip_edittrip.php" , self.__APIDOMAIN];
    return [super doSynHttpPost:url postData:data];
}
- (BOOL)cancelTripByid:(int)id who:(NSInteger)uid
{
    NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
    NSString *uidS = [NSString stringWithFormat:@"%d" , uid];
    [data setObject:id forKey:@"id"];
    [data setObject:uidS forKey:@"uid"];
    NSLog(@"%@" , data);
    NSString *url = [NSString stringWithFormat:@"%@/api_mytrip_canceltrip.php" , self.__APIDOMAIN];
    NSLog(@"%@" , url);
    return [super doSynHttpPost:url postData:data];
}
@end
